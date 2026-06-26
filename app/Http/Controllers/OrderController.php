<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Support\SimplePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $serverCart = $this->cartPayload();

        return view('checkout', compact('serverCart'));
    }

    /**
     * Memproses pesanan masuk, menyimpan ke database, dan menghitung nominal belanja & ongkir secara aman.
     */
    public function process(CheckoutRequest $request)
    {
        $cartItems = $this->cartPayload();
        if (empty($cartItems)) {
            $cartItems = json_decode($request->cart_data, true);
        }

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
                
                if ($product->stock < $quantity) {
                    throw new \Exception("Stok produk '{$product->name}' tidak mencukupi. Tersedia: {$product->stock}, dipesan: {$quantity}.");
                }

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
            $isPickup = $request->delivery_method === 'pickup';
            $verifiedCost = null;
            $verifiedService = null;
            $shippingCost = 0;

            if (!$isPickup) {
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

                            $activeCouriers = Setting::getValue('active_couriers', 'jne,jnt');
                            $response = Http::withHeaders([
                                'authorization' => $apiKey
                            ])->post($endpoint, [
                                'origin_postal_code' => $originPostalCode,
                                'destination_postal_code' => $destPostalCode,
                                'couriers' => $activeCouriers,
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
            } else {
                $shippingCost = 0;
            }

            $coupon = null;
            $discountAmount = 0;
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
                if (!$coupon) {
                    throw new \Exception('Kode promo tidak ditemukan.');
                }

                $discountAmount = $coupon->discountFor($subtotal);
                if ($discountAmount <= 0) {
                    throw new \Exception('Kode promo tidak berlaku untuk pesanan ini.');
                }
            }

            $totalPaid = max(0, $subtotal - $discountAmount) + $shippingCost;

            // Generate Transaction ID unik berformat KS9-****** (6 digit angka acak)
            do {
                $transactionId = 'KS9-' . mt_rand(100000, 999999);
                $exists = Order::where('transaction_id', $transactionId)->exists();
            } while ($exists);

            if ($isPickup) {
                $courierVal = 'pickup';
                $serviceVal = 'pickup';
                $shippingAddress = "Ambil di Toko (Local Pickup)";
                $city = "Tuban";
                $postalCode = "62311";
                $areaId = null;
                $areaName = null;
            } else {
                $courierVal = $request->courier;
                $serviceVal = $request->shipping_service;
                $shippingAddress = $request->address;
                $city = $request->city;
                $postalCode = $request->postal_code;
                $areaId = $request->biteship_area_id;
                $areaName = $request->biteship_area_name;
            }

            $order = Order::create([
                'transaction_id' => $transactionId,
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'shipping_address' => $shippingAddress,
                'city' => $city,
                'postal_code' => $postalCode,
                'biteship_area_id' => $areaId,
                'biteship_area_name' => $areaName,
                'order_notes' => $request->order_notes, // Catatan murni dari user
                'payment_method' => $request->payment,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'coupon_code' => $coupon?->code,
                'discount_amount' => $discountAmount,
                'total_paid' => $totalPaid,
                'status' => 'Awaiting Payment',
                'courier' => $courierVal,
                'shipping_service' => $serviceVal,
            ]);

            // Simpan detail Order Items & kurangi stok
            foreach ($itemsToSave as $itemData) {
                $order->items()->create($itemData);
                
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    $newStock = max(0, $product->stock - $itemData['quantity']);
                    $updateData = ['stock' => $newStock];
                    if ($newStock === 0) {
                        $updateData['status'] = 'SOLD OUT';
                    }
                    $product->update($updateData);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Update profile address if logged in
            if (\Illuminate\Support\Facades\Auth::check()) {
                if (!$isPickup) {
                    \Illuminate\Support\Facades\Auth::user()->update([
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'postal_code' => $request->postal_code,
                        'biteship_area_id' => $request->biteship_area_id,
                        'biteship_area_name' => $request->biteship_area_name,
                    ]);
                } else {
                    \Illuminate\Support\Facades\Auth::user()->update([
                        'phone' => $request->phone,
                    ]);
                }
            }

            DB::commit();

            $this->clearCart();

            // Kirim email konfirmasi ke pelanggan
            try {
                \Illuminate\Support\Facades\Mail::to($order->email)->send(new \App\Mail\OrderCreated($order));
            } catch (\Exception $mailEx) {
                Log::error('Mail Sending Failed: ' . $mailEx->getMessage());
            }

            // Kembalikan respons redirect sukses ke halaman pembayaran
            return redirect()->route('order.payment', $order->uuid)
                ->with('checkout_success', 'Pesanan Anda berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            
            $productsStock = Product::select('id', 'name', 'stock', 'status')->get()->keyBy('id')->toArray();
            
            return redirect()->back()
                ->withInput()
                ->with('sync_products_stock', $productsStock)
                ->withErrors(['error' => 'Gagal memproses pesanan: ' . $e->getMessage()]);
        }
    }

    public function payment($uuid)
    {
        $order = Order::with('items.product')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('payment', compact('order'));
    }

    public function tracking($uuid)
    {
        $order = Order::with('items.product')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $this->syncTracking($order);

        return view('shipping', compact('order'));
    }

    public function uploadPaymentProof(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:4096'],
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $order->update([
            'payment_proof_path' => $path,
            'payment_proof_uploaded_at' => now(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Tim kami akan memverifikasi secara manual.');
    }

    public function downloadInvoice($uuid)
    {
        $order = Order::with('items')->where('uuid', $uuid)->firstOrFail();
        return view('order.receipt', compact('order'));
    }

    public function showTrackingForm()
    {
        return view('tracking_lookup');
    }

    public function findOrder(Request $request)
    {
        $request->validate([
            'transaction_id' => ['required', 'string'],
            'email_or_phone' => ['required', 'string'],
        ]);

        $transactionId = strtoupper(trim($request->transaction_id));
        $emailOrPhone = trim($request->email_or_phone);

        // Normalisasi nomor telepon
        $cleanInput = preg_replace('/[^0-9]/', '', $emailOrPhone);

        $order = Order::where(function ($q) use ($transactionId) {
            $q->where('transaction_id', $transactionId)
              ->orWhere('transaction_id', strtolower($transactionId));
        })
        ->where(function ($query) use ($emailOrPhone, $cleanInput) {
            $query->where('email', $emailOrPhone)
                  ->orWhere('email', strtolower($emailOrPhone))
                  ->orWhere('phone', $emailOrPhone);
            
            if (!empty($cleanInput)) {
                // Konversi 628... ke 08...
                if (str_starts_with($cleanInput, '62')) {
                    $altPhone = '0' . substr($cleanInput, 2);
                    $query->orWhere('phone', $altPhone)
                          ->orWhere('phone', $cleanInput);
                } 
                // Konversi 08... ke 628...
                elseif (str_starts_with($cleanInput, '0')) {
                    $altPhone = '62' . substr($cleanInput, 1);
                    $query->orWhere('phone', $altPhone)
                          ->orWhere('phone', $cleanInput);
                }
                
                $query->orWhere('phone', $cleanInput);
            }
        })
        ->first();

        if (!$order) {
            return back()
                ->withErrors(['tracking_error' => 'Pesanan tidak ditemukan. Pastikan data yang dimasukkan benar.'])
                ->withInput();
        }

        if (empty($order->uuid)) {
            $order->uuid = (string) \Illuminate\Support\Str::uuid();
            $order->save();
        }

        return redirect()->route('order.tracking', $order->uuid);
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

            $activeCouriers = Setting::getValue('active_couriers', 'jne,jnt');
            $response = Http::withHeaders([
                'authorization' => $apiKey
            ])->post($endpoint, [
                'origin_postal_code' => $originPostalCode,
                'destination_postal_code' => $destPostalCode,
                'couriers' => $activeCouriers,
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

    private function cartPayload(): array
    {
        $items = CartItem::with('product')
            ->when(Auth::check(), fn ($query) => $query->where('user_id', Auth::id()))
            ->unless(Auth::check(), fn ($query) => $query->where('session_id', session()->getId()))
            ->get();

        return $items->filter(fn ($item) => $item->product)
            ->map(function ($item) {
                $product = $item->product;
                $price = (float) $product->price;
                foreach (($product->sizes ?? []) as $size) {
                    if (($size['size'] ?? null) === $item->grind_size) {
                        $price = (float) $size['price'];
                    }
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'grind_size' => $item->grind_size,
                    'quantity' => $item->quantity,
                    'image' => $product->image_path ? asset($product->image_path) : '',
                ];
            })->values()->all();
    }

    private function clearCart(): void
    {
        CartItem::when(Auth::check(), fn ($query) => $query->where('user_id', Auth::id()))
            ->unless(Auth::check(), fn ($query) => $query->where('session_id', session()->getId()))
            ->delete();
    }

    private function syncTracking(Order $order): void
    {
        if (!$order->tracking_number || !$order->courier || $order->courier === 'pickup') {
            return;
        }

        if ($order->tracking_synced_at && $order->tracking_synced_at->gt(now()->subMinutes(20))) {
            return;
        }

        if (config('services.biteship.mock', false)) {
            $order->update([
                'tracking_events' => [
                    ['datetime' => now()->subHours(4)->toDateTimeString(), 'status' => 'Paket diterima kurir', 'note' => 'Tuban'],
                    ['datetime' => now()->subHour()->toDateTimeString(), 'status' => 'Dalam perjalanan', 'note' => 'Transit hub'],
                ],
                'tracking_synced_at' => now(),
            ]);
            return;
        }

        $apiKey = config('services.biteship.api_key');
        if (!$apiKey) {
            return;
        }

        try {
            $baseUrl = rtrim(config('services.biteship.base_url', 'https://api.biteship.com/v1'), '/');
            $response = Http::withHeaders(['authorization' => $apiKey])
                ->get($baseUrl . '/trackings/' . urlencode($order->tracking_number) . '/couriers/' . urlencode($order->courier));

            if (!$response->successful()) {
                Log::warning('Biteship tracking failed: ' . $response->body());
                return;
            }

            $data = $response->json();
            $history = $data['history'] ?? $data['data']['history'] ?? [];
            $events = collect($history)->map(fn ($event) => [
                'datetime' => $event['updated_at'] ?? $event['created_at'] ?? $event['datetime'] ?? null,
                'status' => $event['status'] ?? $event['message'] ?? 'Update pengiriman',
                'note' => $event['note'] ?? $event['location'] ?? '',
            ])->values()->all();

            $order->update([
                'tracking_events' => $events,
                'tracking_synced_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Biteship tracking exception: ' . $e->getMessage());
        }
    }

    public function confirmDelivery($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        
        if ($order->status === 'Shipped') {
            $order->update([
                'status' => 'Delivered'
            ]);

            return redirect()->back()->with('confirm_success', 'Terima kasih! Pesanan Anda telah ditandai sebagai Diterima.');
        }

        return redirect()->back()->withErrors(['error' => 'Pesanan tidak dalam status pengiriman.']);
    }
}
