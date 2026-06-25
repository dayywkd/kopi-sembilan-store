<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman cetak resi / invoice untuk pesanan.
     */
    public function printReceipt($transaction_id)
    {
        $order = Order::with('items')->where('transaction_id', $transaction_id)->firstOrFail();
        return view('admin.print_receipt', compact('order'));
    }
}
