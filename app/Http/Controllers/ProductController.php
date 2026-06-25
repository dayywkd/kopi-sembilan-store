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
        
        $query = Product::with(['category', 'reviews']);

        // Filter Category
        if ($request->filled('category') && $request->category !== 'ALL') {
            $query->where('category_id', $request->category);
        }

        // Filter Pencarian (Search)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('sensory_notes', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sorting
        $sort = $request->query('sort', 'best_seller');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'best_seller') {
            $query->orderByRaw('is_best_seller DESC')->orderBy('created_at', 'desc');
        } else {
            $query->orderByRaw('is_best_seller DESC')->orderBy('created_at', 'desc');
        }

        $products = $query->get();

        // Filter Weight (PHP Collection filter)
        if ($request->filled('weight') && $request->weight !== 'ALL') {
            $weightFilter = $request->weight;
            $products = $products->filter(function($product) use ($weightFilter) {
                if (empty($product->sizes) || !is_array($product->sizes)) {
                    return strtolower($weightFilter) === '250g' || strtolower($weightFilter) === '250gr';
                }
                foreach ($product->sizes as $sizeOption) {
                    $sizeName = strtolower(str_replace(['gr', 'grams', ' '], ['g', 'g', ''], $sizeOption['size']));
                    $filterName = strtolower(str_replace(['gr', 'grams', ' '], ['g', 'g', ''], $weightFilter));
                    if ($sizeName === $filterName) {
                        return true;
                    }
                }
                return false;
            });
        }
        
        $defaultCategory = $request->query('category', 'ALL');
        $defaultWeight = $request->query('weight', 'ALL');
        $defaultSort = $sort;

        return view('shop', compact('categories', 'products', 'defaultCategory', 'defaultWeight', 'defaultSort'));
    }

    /**
     * Menampilkan halaman detail produk kopi secara dinamis.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'reviews', 'images'])->where('slug', $slug)->firstOrFail();
        
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
            'is_approved' => false,
        ]);

        return redirect()->back()->with('review_success', 'Terima kasih! Ulasan Anda masuk antrean moderasi admin.');
    }
}
