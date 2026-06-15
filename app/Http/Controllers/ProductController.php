<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan katalog produk (Shop) dinamis.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::with('category')->get();
        
        // Membaca category filter default dari query parameter jika diakses dari luar (misal footer)
        $defaultCategory = $request->query('category', 'ALL');

        return view('shop', compact('categories', 'products', 'defaultCategory'));
    }

    /**
     * Menampilkan halaman detail produk kopi secara dinamis.
     */
    public function show($slug)
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        
        // Ambil grind sizes secara dinamis dari database (Opsi B)
        $grindSizes = Setting::getValue('grind_sizes', [
            'WHOLE BEAN',
            'ESPRESSO (FINE)',
            'POUR OVER (MEDIUM)',
            'FRENCH PRESS (COARSE)'
        ]);

        return view('detailproduk', compact('product', 'grindSizes'));
    }
}
