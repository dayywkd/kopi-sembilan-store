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
        $products = Product::with(['category', 'reviews'])->get();
        
        // Membaca category filter default dari query parameter jika diakses dari luar (misal footer)
        $defaultCategory = $request->query('category', 'ALL');

        return view('shop', compact('categories', 'products', 'defaultCategory'));
    }

    /**
     * Menampilkan halaman detail produk kopi secara dinamis.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'reviews'])->where('slug', $slug)->firstOrFail();
        
        // Ambil grind sizes secara dinamis dari database (Opsi B)
        $grindSizes = Setting::getValue('grind_sizes', [
            'WHOLE BEAN',
            'ESPRESSO (FINE)',
            'POUR OVER (MEDIUM)',
            'FRENCH PRESS (COARSE)'
        ]);

        return view('detailproduk', compact('product', 'grindSizes'));
    }

    /**
     * Menyimpan ulasan produk dari pelanggan.
     */
    public function storeReview(Request $request, $product_id)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $product = Product::findOrFail($product_id);

        $product->reviews()->create([
            'customer_name' => $request->customer_name,
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('review_success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
