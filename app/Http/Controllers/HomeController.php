<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman depan (landing page) dinamis.
     */
    public function index()
    {
        // Mengambil 3 produk terbaru untuk ditampilkan sebagai produk unggulan (Featured Products)
        $featuredProducts = Product::with(['category', 'reviews'])
            ->where('status', '!=', 'SOLD OUT')
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}
