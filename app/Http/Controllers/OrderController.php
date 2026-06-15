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

        // Ambil konfigurasi ongkir dinamis dari tabel settings (Opsi B)
        $shippingThreshold = (int) Setting::getValue('shipping_threshold', 500000);
        $shippingFlatRate = (int) Setting::getValue('shipping_flat_rate', 15000);

        // Bungkus dalam database transaction untuk keamanan integrasi data
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $itemsToSave = [];

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

                // Siapkan data item untuk disimpan nanti
                $itemsToSave[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'grind_size' => $selectedSize,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // Hitung biaya pengiriman (ongkir) secara terstruktur di backend
            $shippingCost = $subtotal >= $shippingThreshold ? 0 : $shippingFlatRate;
            $totalPaid = $subtotal + $shippingCost;

            // Generate Transaction ID unik berformat TK9-****** (6 digit angka acak)
            do {
                $transactionId = 'TK9-' . mt_rand(100000, 900000);
                $exists = Order::where('transaction_id', $transactionId)->exists();
            } while ($exists);

            // Simpan data utama Order
            $order = Order::create([
                'transaction_id' => $transactionId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'shipping_address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'order_notes' => $request->order_notes,
                'payment_method' => $request->payment,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_paid' => $totalPaid,
                'status' => 'Paid', // Default Paid sesuai spesifikasi
            ]);

            // Simpan detail Order Items
            foreach ($itemsToSave as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();

            // Kembalikan respons redirect sukses dengan flash data berisi ID Transaksi & data order terakhir
            return redirect()->route('order.tracking', $transactionId)
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
     * Menampilkan halaman pelacakan pesanan sukses.
     */
    public function tracking($transaction_id)
    {
        $order = Order::with('items.product')
            ->where('transaction_id', $transaction_id)
            ->firstOrFail();

        return view('shipping', compact('order'));
    }
}
