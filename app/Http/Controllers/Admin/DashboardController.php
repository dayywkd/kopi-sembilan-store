<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama panel admin beserta statistik penjualan & operasional.
     */
    public function index()
    {
        $orders = Order::latest()->get();

        // Hitung statistik secara dinamis dari database orders
        $awaitingRoast = Order::whereIn('status', ['Paid', 'Packing'])->count();
        $shippedVolume = Order::where('status', 'Shipped')->count();
        $totalRevenue = Order::sum('total_paid');
        $avgFulfillment = '1.8 hrs'; // Dummy constant sesuai spesifikasi UI

        return view('admin.dashboard', compact(
            'orders', 
            'awaitingRoast', 
            'shippedVolume', 
            'totalRevenue', 
            'avgFulfillment'
        ));
    }

    /**
     * Memperbarui status pemenuhan pesanan (Fulfillment Status) dari modal popup.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => ['required', 'string', 'exists:orders,transaction_id'],
            'status' => ['required', 'string', 'in:Paid,Packing,Shipped'],
        ]);

        $order = Order::where('transaction_id', $request->transaction_id)->firstOrFail();
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.dashboard')
            ->with('status_updated', "Status pesanan #{$order->transaction_id} berhasil diubah menjadi {$request->status}.");
    }
}
