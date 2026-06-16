<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman formulir checkout.
     */
    public function checkout()
    {
        return view('checkout');
    }

    /**
     * Memproses pesanan masuk, menyimpan ke database, dan menghitung nominal belanja & ongkir secara aman.
     */
    public function process(CheckoutRequest $request)
    {
        // Decode JSON data belanja dari client
        $cartItems = json_decode($request->cart_data, true);

        if (!$cartItems || count($cartItems) === 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cart_data' => 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.']);
        }

        // Bungkus dalam database transaction untuk keamanan integrasi data
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsToSave = [];
            $totalWeight = 0;

            // Validasi produk dan hitung subtotal secara aman (mencegah manipulasi harga dari sisi client)
            foreach ($cartItems as $item) {
                $product = Product::find($item['id']);
                
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                }

                if ($product->status === 'SOLD OUT') {
                    throw new \Exception("Produk '{$product->name}' saat ini sedang habis.");
                }

                $selectedSize = $item['grind_size'] ?? '100gr';
                $price = $product->price;

                // Temukan harga dari array sizes JSON jika tersedia
                if ($product->sizes && is_array($product->sizes)) {
                    foreach ($product->sizes as $sizeOption) {
                        if ($sizeOption['size'] === $selectedSize) {
                            $price = (float) $sizeOption['price'];
                            break;
                        }
                    }
                }

                $quantity = (int) $item['quantity'];
                $itemTotal = $price * $quantity;
                $subtotal += $itemTotal;

                // Hitung berat total dalam gram
                $totalWeight += $this->parseWeightInGrams($selectedSize) * $quantity;

                // Siapkan data item untuk disimpan nanti
                $itemsToSave[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'grind_size' => $selectedSize,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            if ($totalWeight <= 0) {
                $totalWeight = 1000;
            }

            // Validasi ongkir di backend menggunakan Biteship (Area ID)
            $verifiedCost = null;
            $verifiedService = null;
            if (config('services.biteship.mock', false)) {
                $mockCosts = $this->getMockCosts($request->courier);
                foreach ($mockCosts as $c) {
                    if (strtolower($c['service']) === strtolower($request->shipping_service)) {
                        $verifiedCost = (float) $c['price'];
                        $verifiedService = $c['service'];
                        break;
                    }
                }
            } else {
                $apiKey = config('services.biteship.api_key');
                $baseUrl = config('services.biteship.base_url', 'https://api.biteship.com/v1');
                $endpoint = rtrim($baseUrl, '/') . '/rates';
                if (substr($endpoint, -6) === '/rates') {
                    $endpoint .= '/couriers';
                }

                if ($apiKey && $request->postal_code) {
                    try {
                        $destPostalCode = (int) $request->postal_code;
                        $originPostalCode = 62311; // Kode pos toko (Tuban)

                        // Map items
                        $items = [];
                        foreach ($cartItems as $item) {
                            $product = Product::find($item['id']);
                            $name = $product ? $product->name : $item['name'];
                            $selectedSize = $item['grind_size'] ?? '100gr';
                            $weight = $this->parseWeightInGrams($selectedSize);
                            $price = $product ? $product->price : (float) $item['price'];
                            if ($product && $product->sizes && is_array($product->sizes)) {
                                foreach ($product->sizes as $sizeOption) {
                                    if ($sizeOption['size'] === $selectedSize) {
                                        $price = (float) $sizeOption['price'];
                                        break;
                                    }
                                }
                            }

                            $items[] = [
                                'name' => $name . ' (' . $selectedSize . ')',
                                'quantity' => (int) $item['quantity'],
                                'value' => (float) $price,
                                'weight' => $weight
                            ];
                        }

                        $response = Http::withHeaders([
                            'authorization' => $apiKey
                        ])->post($endpoint, [
                            'origin_postal_code' => $originPostalCode,
                            'destination_postal_code' => $destPostalCode,
                            'couriers' => 'jne,jnt,sicepat,anteraja,tiki,pos,lion,idexpress,wahana,sap,ninja',
                            'items' => $items,
                        ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $pricings = $data['pricing'] ?? $data['data']['pricing'] ?? [];
                            $chosenCourier = strtolower($request->courier);
                            $chosenService = strtolower($request->shipping_service);

                            foreach ($pricings as $pricing) {
                                $serviceName = $pricing['courier_service_name'] ?? $pricing['courier_service_code'];
                                if (strtolower($pricing['courier_code']) === $chosenCourier && strtolower($serviceName) === $chosenService) {
                                    $verifiedCost = (float) ($pricing['price'] ?? 0);
                                    $verifiedService = $serviceName;
                                    break;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error('Biteship validation failed: ' . $e->getMessage());
                    }
                }
            }

            // Jika berhasil diverifikasi, gunakan verified cost, jika gagal, gunakan cost dari request
            $shippingCost = $verifiedCost !== null ? $verifiedCost : (float) $request->shipping_cost;

            // Terapkan Free Shipping Threshold
            $shippingThreshold = (int) Setting::getValue('shipping_threshold', 500000);
            if ($subtotal >= $shippingThreshold) {
                $shippingCost = 0;
            }

            if ($shippingCost < 0) {
                $shippingCost = 0;
            }

            $baseTotal = $subtotal + $shippingCost;

            // Bulatkan ke ribuan terdekat dan tambahkan 3-digit kode unik acak (100 s.d 999)
            $roundedTotal = floor($baseTotal / 1000) * 1000;
            $uniqueCode = mt_rand(100, 999);
            $totalPaid = $roundedTotal + $uniqueCode;

            // Generate Transaction ID unik berformat TK9-****** (6 digit angka acak)
            do {
                $transactionId = 'TK9-' . mt_rand(100000, 900000);
                $exists = Order::where('transaction_id', $transactionId)->exists();
            } while ($exists);

            // Sisipkan info kurir langsung ke order_notes
            $formattedCourier = strtoupper($request->courier) . ' - ' . ($verifiedService ?? $request->shipping_service);
            $prefix = "[Kurir: {$formattedCourier}]";
            $finalNotes = $request->order_notes ? $prefix . "\n" . $request->order_notes : $prefix;

            $order = Order::create([
                'transaction_id' => $transactionId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'shipping_address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'biteship_area_id' => $request->biteship_area_id,
                'biteship_area_name' => $request->biteship_area_name,
                'order_notes' => $finalNotes,
                'payment_method' => $request->payment,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_paid' => $totalPaid,
                'status' => 'Awaiting Payment', // Default Awaiting Payment sesuai spesifikasi PRD V6
            ]);

            // Simpan detail Order Items
            foreach ($itemsToSave as $itemData) {
                $order->items()->create($itemData);
            }

            // Update profile address if logged in
            if (\Illuminate\Support\Facades\Auth::check()) {
                \Illuminate\Support\Facades\Auth::user()->update([
                    'address' => $request->address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                    'biteship_area_id' => $request->biteship_area_id,
                    'biteship_area_name' => $request->biteship_area_name,
                ]);
            }

            DB::commit();

            // Kembalikan respons redirect sukses ke halaman pembayaran
            return redirect()->route('order.payment', $transactionId)
                ->with('checkout_success', 'Pesanan Anda berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memproses pesanan: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan halaman instruksi pembayaran.
     */
    public function payment($transaction_id)
    {
        $order = Order::with('items.product')
            ->where('transaction_id', $transaction_id)
            ->firstOrFail();

        return view('payment', compact('order'));
    }

    /**
     * Menampilkan halaman pelacakan pesanan sukses.
     */
    public function tracking($transaction_id)
    {
        $order = Order::with('items.product')
            ->where('transaction_id', $transaction_id)
            ->firstOrFail();

        return view('shipping', compact('order'));
    }

    /**
     * Mengambil daftar kota (legacy stub).
     */
    public function getCities()
    {
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }

    /**
     * Mencari area Biteship berdasarkan input teks (Kecamatan, Kota, Kode Pos).
     */
    public function searchAreas(Request $request)
    {
        $input = $request->query('input');
        if (strlen($input) < 3) {
            return response()->json([
                'success' => true,
                'areas' => []
            ]);
        }

        if (config('services.biteship.mock', false)) {
            // Mock response untuk area search jika Biteship di-mock
            $mockAreas = [
                [
                    'id' => 'ID-JA-TU-TU-DO',
                    'name' => 'Kutorejo, Tuban, Jawa Timur. 62311',
                    'postal_code' => '62311',
                    'administrative_division_level_1_name' => 'Jawa Timur',
                    'administrative_division_level_2_name' => 'Tuban',
                ],
                [
                    'id' => 'ID-JA-SU-SU-MA',
                    'name' => 'Margorejo, Wonocolo, Surabaya, Jawa Timur. 60238',
                    'postal_code' => '60238',
                    'administrative_division_level_1_name' => 'Jawa Timur',
                    'administrative_division_level_2_name' => 'Surabaya',
                ],
                [
                    'id' => 'ID-JK-JA-KE-BA',
                    'name' => 'Kebayoran Baru, Jakarta Selatan, DKI Jakarta. 12110',
                    'postal_code' => '12110',
                    'administrative_division_level_1_name' => 'DKI Jakarta',
                    'administrative_division_level_2_name' => 'Jakarta Selatan',
                ]
            ];

            // Filter sederhana
            $filtered = array_filter($mockAreas, function($area) use ($input) {
                return stripos($area['name'], $input) !== false || stripos((string)$area['postal_code'], $input) !== false;
            });

            return response()->json([
                'success' => true,
                'areas' => array_values($filtered)
            ]);
        }

        $apiKey = config('services.biteship.api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Biteship belum dikonfigurasi.'
            ]);
        }

        try {
            $response = Http::withHeaders(['authorization' => $apiKey])
                ->get("https://api.biteship.com/v1/maps/areas", [
                    'countries' => 'ID',
                    'input' => $input
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'areas' => $data['areas'] ?? []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memanggil API Biteship: ' . $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Biteship Search Areas Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mencari area.'
            ]);
        }
    }

    /**
     * Menghitung ongkos kirim berdasarkan berat belanjaan & area ID tujuan menggunakan Biteship.
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'biteship_area_id' => 'nullable|string|max:100',
            'destination_postal_code' => 'required|string|max:10',
            'cart_data' => 'required|string',
        ]);

        if (config('services.biteship.mock', false)) {
            $couriers = ['jne', 'jnt', 'sicepat'];
            $rates = [];
            foreach ($couriers as $courier) {
                $mockCosts = $this->getMockCosts($courier);
                foreach ($mockCosts as $c) {
                    $rates[] = [
                        'courier' => $courier,
                        'service' => $c['service'],
                        'cost' => (float) $c['price'],
                        'etd' => '1 - 3 Hari'
                    ];
                }
            }
            return response()->json([
                'success' => true,
                'rates' => $rates
            ]);
        }

        $apiKey = config('services.biteship.api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key Biteship belum dikonfigurasi.'
            ]);
        }

        $cartItems = json_decode($request->cart_data, true);
        if (empty($cartItems)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja kosong.'
            ]);
        }

        // Hitung total berat dalam gram & siapkan list item
        $totalWeight = 0;
        $items = [];
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            $name = $product ? $product->name : $item['name'];
            $selectedSize = $item['grind_size'] ?? '100gr';
            $weight = $this->parseWeightInGrams($selectedSize);
            
            $price = $product ? $product->price : (float) $item['price'];
            if ($product && $product->sizes && is_array($product->sizes)) {
                foreach ($product->sizes as $sizeOption) {
                    if ($sizeOption['size'] === $selectedSize) {
                        $price = (float) $sizeOption['price'];
                        break;
                    }
                }
            }

            $items[] = [
                'name' => $name . ' (' . $selectedSize . ')',
                'quantity' => (int) $item['quantity'],
                'value' => (float) $price,
                'weight' => $weight
            ];
            $totalWeight += $weight * (int) $item['quantity'];
        }

        $baseUrl = config('services.biteship.base_url', 'https://api.biteship.com/v1');
        $endpoint = rtrim($baseUrl, '/') . '/rates';
        if (substr($endpoint, -6) === '/rates') {
            $endpoint .= '/couriers';
        }

        try {
            $destPostalCode = (int) $request->destination_postal_code;
            $originPostalCode = 62311; // Kode pos toko (Tuban)

            $response = Http::withHeaders([
                'authorization' => $apiKey
            ])->post($endpoint, [
                'origin_postal_code' => $originPostalCode,
                'destination_postal_code' => $destPostalCode,
                'couriers' => 'jne,jnt,sicepat,anteraja,tiki,pos,lion,idexpress,wahana,sap,ninja',
                'items' => $items,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pricings = $data['pricing'] ?? $data['data']['pricing'] ?? [];
                
                $rates = [];
                foreach ($pricings as $pricing) {
                    $rates[] = [
                        'courier' => strtolower($pricing['courier_code']),
                        'service' => $pricing['courier_service_name'] ?? $pricing['courier_service_code'],
                        'cost' => (float) ($pricing['price'] ?? 0),
                        'etd' => $pricing['duration'] ?? ''
                    ];
                }

                return response()->json([
                    'success' => true,
                    'rates' => $rates
                ]);
            }

            $errorBody = $response->json();
            $errorCode = $errorBody['code'] ?? null;
            $errorMsg = $errorBody['error'] ?? 'Gagal mendapatkan tarif pengiriman.';
            Log::error('Biteship Rates API error: ' . $response->body());

            // Code 40001010: No courier available for the requested location
            if ($errorCode === 40001010) {
                return response()->json([
                    'success' => false,
                    'code' => $errorCode,
                    'message' => 'Tidak ada kurir yang tersedia untuk wilayah ini. Coba wilayah lain atau gunakan tombol WhatsApp untuk memesan.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $errorMsg
            ]);
        } catch (\Exception $e) {
            Log::error('Biteship Rates Exception: ' . $e->getMessage());
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan tarif pengiriman dari Biteship.'
        ]);
    }

    /**
     * Mem-parse string berat ke gram (misal: 100gr -> 100, 1kg -> 1000).
     */
    private function parseWeightInGrams($sizeString)
    {
        $sizeString = strtolower(trim($sizeString));

        if (strpos($sizeString, 'kg') !== false) {
            $num = (float) str_replace('kg', '', $sizeString);
            return (int) ($num * 1000);
        }

        if (strpos($sizeString, 'gr') !== false) {
            return (int) str_replace('gr', '', $sizeString);
        }

        if (strpos($sizeString, 'g') !== false) {
            return (int) str_replace('g', '', $sizeString);
        }

        $val = (int) $sizeString;
        return $val > 0 ? $val : 250;
    }

    /**
     * Data mock biaya pengiriman Biteship untuk development.
     */
    private function getMockCosts($courier)
    {
        $courier = strtolower($courier);
        if ($courier === 'jne') {
            return [
                ['service' => 'REG', 'price' => 15000],
                ['service' => 'OKE', 'price' => 12000],
            ];
        } elseif ($courier === 'jnt') {
            return [
                ['service' => 'EZ', 'price' => 14000],
                ['service' => 'ECO', 'price' => 11000],
            ];
        } elseif ($courier === 'sicepat') {
            return [
                ['service' => 'SIUNTUNG', 'price' => 13000],
                ['service' => 'HALU', 'price' => 10000],
            ];
        }
        return [];
    }

    /**
     * Konfirmasi bahwa pesanan telah diterima oleh customer.
     */
    public function confirmDelivery($transaction_id)
    {
        $order = Order::where('transaction_id', $transaction_id)->firstOrFail();
        
        if ($order->status === 'Shipped') {
            $order->update([
                'status' => 'Delivered'
            ]);
            return redirect()->back()->with('confirm_success', 'Terima kasih! Pesanan Anda telah ditandai sebagai Diterima.');
        }

        return redirect()->back()->withErrors(['error' => 'Pesanan tidak dalam status pengiriman.']);
    }
}
