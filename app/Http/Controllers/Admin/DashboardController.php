<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama panel admin beserta statistik penjualan & operasional.
     */
    public function index()
    {
        // Urutkan pesanan berstatus 'Awaiting Payment' di paling atas (PRD V6)
        $orders = Order::orderByRaw("CASE WHEN status = 'Awaiting Payment' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik secara dinamis dari database orders sesuai PRD V6
        $awaitingRoast = Order::whereIn('status', ['Paid', 'Packing'])->count();
        $shippedVolume = Order::where('status', 'Shipped')->count();
        $totalRevenue = Order::where('status', 'Paid')->sum('total_paid');
        $avgFulfillment = '1.8 hrs'; // Dummy constant sesuai spesifikasi UI

        // Muat produk untuk pengelolaan Best Seller
        $products = \App\Models\Product::orderBy('name')->get();

        // Muat kurir yang saat ini aktif
        $activeCouriersString = \App\Models\Setting::getValue('active_couriers', 'jne,jnt');
        $activeCouriers = explode(',', $activeCouriersString);

        // Muat pelanggan (non-admin)
        $customers = \App\Models\User::where(function($query) {
            $query->where('role', '!=', 'admin')
                  ->orWhereNull('role');
        })->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'orders', 
            'awaitingRoast', 
            'shippedVolume', 
            'totalRevenue', 
            'avgFulfillment',
            'products',
            'activeCouriers',
            'customers'
        ));
    }

    /**
     * Memperbarui status pemenuhan pesanan (Fulfillment Status) dari modal popup.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => ['required', 'string', 'exists:orders,transaction_id'],
            'status' => ['required', 'string', 'in:Awaiting Payment,Paid,Packing,Shipped,Delivered'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        $order = Order::where('transaction_id', $request->transaction_id)->firstOrFail();
        
        $updateData = [
            'status' => $request->status,
        ];

        if ($request->filled('tracking_number')) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        $order->update($updateData);

        // Kirim email notifikasi perubahan status
        try {
            \Illuminate\Support\Facades\Mail::to($order->email)->send(new \App\Mail\OrderStatusChanged($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Gagal mengirim email update status #{$order->transaction_id}: " . $e->getMessage());
        }

        return redirect()->route('admin.dashboard')
            ->with('status_updated', "Status pesanan #{$order->transaction_id} berhasil diubah menjadi {$request->status}.");
    }

    /**
     * Tampilkan halaman cetak resi / invoice untuk pesanan.
     */
    public function printReceipt($transaction_id)
    {
        $order = Order::with('items')->where('transaction_id', $transaction_id)->firstOrFail();
        return view('admin.print_receipt', compact('order'));
    }

    /**
     * Mengaktifkan/menonaktifkan status Best Seller dari produk.
     */
    public function toggleBestSeller($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->is_best_seller = !$product->is_best_seller;
        $product->save();

        return response()->json([
            'success' => true,
            'is_best_seller' => $product->is_best_seller,
            'message' => 'Status Best Seller untuk ' . $product->name . ' berhasil diperbarui.'
        ]);
    }

    /**
     * Memperbarui pengaturan kurir aktif Biteship.
     */
    public function updateCouriers(Request $request)
    {
        $request->validate([
            'couriers' => ['required', 'array'],
        ]);

        $couriersString = implode(',', $request->couriers);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'active_couriers'],
            ['value' => $couriersString]
        );

        return redirect()->route('admin.dashboard')
            ->with('status_updated', 'Pengaturan kurir aktif berhasil diperbarui.');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'roast_level' => ['required', 'string', 'in:Light,Medium-Light,Medium,Medium-Dark'],
            'altitude' => ['required', 'string', 'max:255'],
            'sensory_notes' => ['required', 'string', 'max:255'],
            'sizes' => ['required', 'string'],
            'status' => ['required', 'string', 'in:AVAILABLE,LIMITED,SOLD OUT'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        $sizes = json_decode($request->sizes, true);
        if (!$sizes) {
            $sizes = [['size' => $request->sizes, 'price' => (int)$request->price]];
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'sizes' => $sizes,
            'image_path' => $imagePath,
            'roast_level' => $request->roast_level,
            'altitude' => $request->altitude,
            'sensory_notes' => $request->sensory_notes,
            'status' => $request->status,
            'stock' => $request->stock,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('status_updated', 'Produk baru berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'roast_level' => ['required', 'string', 'in:Light,Medium-Light,Medium,Medium-Dark'],
            'altitude' => ['required', 'string', 'max:255'],
            'sensory_notes' => ['required', 'string', 'max:255'],
            'sizes' => ['required', 'string'],
            'status' => ['required', 'string', 'in:AVAILABLE,LIMITED,SOLD OUT'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imagePath = $product->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images/products'), $imageName);
            $imagePath = 'images/products/' . $imageName;
        }

        $sizes = json_decode($request->sizes, true);
        if (!$sizes) {
            $sizes = [['size' => $request->sizes, 'price' => (int)$request->price]];
        }

        $status = $request->status;
        $stock = (int)$request->stock;
        if ($stock === 0) {
            $status = 'SOLD OUT';
        }

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'sizes' => $sizes,
            'image_path' => $imagePath,
            'roast_level' => $request->roast_level,
            'altitude' => $request->altitude,
            'sensory_notes' => $request->sensory_notes,
            'status' => $status,
            'stock' => $stock,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('status_updated', 'Produk berhasil diperbarui.');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image_path && file_exists(public_path($product->image_path))) {
            @unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('admin.dashboard')
            ->with('status_updated', 'Produk berhasil dihapus.');
    }
}
