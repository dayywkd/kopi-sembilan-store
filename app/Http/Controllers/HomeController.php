<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil produk yang ditandai sebagai Best Seller (maksimal 4 produk)
        $featuredProducts = Product::with(['category', 'reviews'])
            ->where('status', '!=', 'SOLD OUT')
            ->where('is_best_seller', true)
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts'));
    }

    /**
     * Menampilkan halaman kemitraan Wholesale B2B.
     */
    public function wholesale()
    {
        return view('wholesale');
    }

    /**
     * Menyimpan formulir pengajuan kerjasama Wholesale B2B.
     */
    public function submitWholesale(Request $request)
    {
        $request->validate([
            'contact_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'contact_name.required' => 'Nama kontak wajib diisi.',
            'business_name.required' => 'Nama bisnis/toko wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'message.required' => 'Pesan kerjasama wajib diisi.',
        ]);

        \App\Models\WholesaleSubmission::create($request->all());

        return redirect()->back()->with('success', 'Formulir kerjasama B2B berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}
