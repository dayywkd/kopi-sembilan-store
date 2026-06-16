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
        // Urutkan pesanan berstatus 'Awaiting Payment' di paling atas (PRD V6)
        $orders = Order::orderByRaw("CASE WHEN status = 'Awaiting Payment' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik secara dinamis dari database orders sesuai PRD V6
        $awaitingRoast = Order::whereIn('status', ['Paid', 'Packing'])->count();
        $shippedVolume = Order::where('status', 'Shipped')->count();
        $totalRevenue = Order::where('status', 'Paid')->sum('total_paid');
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
}
