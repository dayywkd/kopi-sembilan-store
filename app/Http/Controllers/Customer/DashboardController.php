<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama customer beserta riwayat pesanan mereka.
     */
    public function index()
    {
        $orders = Order::where('email', Auth::user()->email)->latest()->get();

        return view('customer.dashboard', compact('orders'));
    }

    /**
     * Memperbarui profil dan alamat pengiriman customer.
     */
    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:15'],
            'biteship_area_id' => ['nullable', 'string', 'max:100'],
            'biteship_area_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'biteship_area_id' => $request->biteship_area_id,
            'biteship_area_name' => $request->biteship_area_name,
        ]);

        return redirect()->back()->with('status', 'Profil dan alamat pengiriman Anda berhasil diperbarui.');
    }
}
