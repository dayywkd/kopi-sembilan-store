<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\Order;
use Illuminate\Http\Request;
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
    public function updateProfile(Request $request)
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

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:60'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:15'],
            'biteship_area_id' => ['nullable', 'string', 'max:100'],
            'biteship_area_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['is_default'] = !$user->addresses()->exists();

        $address = CustomerAddress::create($data);

        if ($address->is_default) {
            $this->syncUserAddress($address);
        }

        return back()->with('status', 'Alamat baru berhasil disimpan.');
    }

    public function setDefaultAddress(CustomerAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        CustomerAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        $this->syncUserAddress($address);

        return back()->with('status', 'Alamat default berhasil diperbarui.');
    }

    public function destroyAddress(CustomerAddress $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);
        $address->delete();

        return back()->with('status', 'Alamat berhasil dihapus.');
    }

    private function syncUserAddress(CustomerAddress $address): void
    {
        Auth::user()->update([
            'phone' => $address->phone,
            'address' => $address->address,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'biteship_area_id' => $address->biteship_area_id,
            'biteship_area_name' => $address->biteship_area_name,
        ]);
    }
}
