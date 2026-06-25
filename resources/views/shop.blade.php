@extends('layouts.app')

@section('title', 'Shop | Toko Kopi Sembilan')
@section('meta_description', 'Jelajahi koleksi biji kopi pilihan (single origin & blend) terbaik kami di Toko Kopi Sembilan. Roasting fresh setiap minggu untuk kesegaran rasa kopi terbaik Anda.')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }
    @media (min-width: 768px) {
        .label-tiny {
            font-size: 12px;
            letter-spacing: 0.25em;
        }
    }
    .product-card {
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #e5e7eb;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    }
    .btn-dark {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #121212;
        transition: all 0.3s ease;
    }
    .btn-dark:hover {
        background-color: transparent;
        border-color: #121212;
        color: #121212;
    }
    .filter-active {
        font-weight: 700;
        border-bottom: 2px solid #121212;
        padding-bottom: 4px;
        color: #121212 !important;
    }
</style>
@endsection

@section('content')
@php
    $categoryImages = [
        'single-origin' => 'images/products/geisha_obsidian.jpg',
        'espresso-blends' => 'images/products/sembilan_zero.jpg',
        'roast-profile' => 'images/products/nebula_eclipse.jpg',
        'gear' => 'images/products/copper_dripper.jpg',
        'subscriptions' => 'images/products/subscription.jpg',
    ];
@endphp
<main class="pt-32 min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop py-4">
        
        <!-- Centered Header -->
        <div class="text-center mb-10">
            <nav class="text-xs text-neutral-400 mb-3 uppercase tracking-widest">
                <a href="{{ route('home') }}" class="hover:text-brand-dark transition-colors">Home</a>
                <span class="mx-2">&middot;</span>
                <span class="text-neutral-600 font-semibold">Speciality Coffee</span>
            </nav>
            <h1 class="font-display text-4xl md:text-5xl italic font-bold text-brand-dark">Speciality Coffee</h1>
        </div>

        <!-- Circular Category Navigation -->
        <div class="flex flex-wrap items-center justify-center gap-6 md:gap-10 my-10">
            @foreach ($categories as $cat)
                @php
                    $imgPath = $categoryImages[$cat->slug] ?? 'images/products/aurora_medium.jpg';
                    $isActive = (string)$defaultCategory === (string)$cat->id;
                    // Jika kategori sudah aktif, mengklik ulang akan membatalkan filter (kembali ke ALL)
                    $targetUrl = $isActive 
                        ? route('shop', request()->except('category')) 
                        : route('shop', array_merge(request()->except('category'), ['category' => $cat->id]));
                @endphp
                <a href="{{ $targetUrl }}" class="group flex flex-col items-center">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-neutral-50 border {{ $isActive ? 'border-2 border-[#121212] ring-4 ring-neutral-100' : 'border-neutral-200 hover:border-[#121212]/50' }} flex items-center justify-center overflow-hidden transition-all duration-300">
                        <img src="{{ asset($imgPath) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $cat->name }}">
                    </div>
                    <span class="text-[10px] md:text-[11px] tracking-widest uppercase mt-3 transition-colors duration-300 {{ $isActive ? 'font-bold text-[#121212] border-b border-[#121212] pb-0.5' : 'font-medium text-neutral-400 group-hover:text-[#121212]' }}">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Filter & Sort Bar -->
        <div class="flex justify-between items-center py-6 border-b border-neutral-200 mb-10 mt-6">
            <div class="text-sm font-bold text-brand-dark uppercase tracking-wider">
                {{ $products->count() }} Products
            </div>
            <button onclick="openFilterDrawer()" class="flex items-center justify-between border border-neutral-300 px-6 py-3 hover:border-brand-dark transition-all duration-200 text-xs font-bold uppercase tracking-wider text-brand-dark w-44">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">tune</span>
                    <span>Filter & Sort</span>
                </div>
                <span class="material-symbols-outlined text-sm">add</span>
            </button>
        </div>

        <!-- Hasil Pencarian -->
        @if (request()->filled('search'))
            <div class="bg-neutral-50 border border-neutral-200 px-6 py-4 flex justify-between items-center mb-8">
                <div class="text-sm text-neutral-600">
                    Hasil pencarian untuk: <span class="font-bold text-[#121212]">"{{ request('search') }}"</span>
                </div>
                <a href="{{ route('shop', request()->except('search')) }}" class="text-xs text-neutral-500 hover:text-[#121212] flex items-center gap-1 font-semibold uppercase tracking-widest">
                    <span class="material-symbols-outlined text-sm">close</span> Bersihkan Pencarian
                </a>
            </div>
        @endif

        <!-- Grid Katalog -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse ($products as $product)
                <!-- Product Card -->
                <div class="product-card bg-white p-6 flex flex-col justify-between h-full">
                    <div>
                        <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-brand-cream mb-6 border border-neutral-100">
                            @if($product->image_path)
                                <img alt="{{ $product->name }}" class="w-full h-full object-cover" src="{{ $product->image_url }}"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-neutral-300 text-xs tracking-widest font-mono">NO IMAGE</div>
                            @endif
                        </a>
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="text-[10px] text-neutral-400 uppercase tracking-widest font-mono">{{ $product->category->name }}</span>
                                <h2 class="text-lg font-bold text-[#121212] hover:text-brand-accent transition-colors mt-1 leading-tight">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h2>
                                <!-- Rating -->
                                <div class="flex items-center gap-1.5 mt-2">
                                    <div class="flex text-brand-accent">
                                        @php $rating = round($product->average_rating); @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' {{ $i <= $rating ? 1 : 0 }}, 'wght' 300, 'GRAD' 0, 'opsz' 24">star</span>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-neutral-400 font-mono">({{ $product->reviews_count }})</span>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-[#121212] whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="mt-4 border-t border-neutral-100 pt-4 space-y-2 text-xs text-neutral-500">
                            @if($product->is_best_seller)
                                <span class="inline-block bg-brand-cream text-brand-accent text-[10px] px-2 py-0.5 font-bold uppercase tracking-wider mb-2 border border-brand-accent/20">Best Seller</span>
                            @endif
                            <div class="flex justify-between">
                                <span class="opacity-70">Altitude:</span>
                                <span class="font-medium text-neutral-700">{{ $product->altitude }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="opacity-70">Roast Level:</span>
                                <span class="font-medium text-neutral-700">{{ $product->roast_level }}</span>
                            </div>
                            <p class="mt-2 line-clamp-2 leading-relaxed italic">"{{ $product->sensory_notes }}"</p>
                        </div>
                    </div>
                    
                    <div class="pt-6">
                        <button onclick="addToBag({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ (is_array($product->sizes) && count($product->sizes) > 0) ? $product->sizes[0]['size'] : '100gr' }}', '{{ $product->image_url }}')" 
                                class="w-full py-3.5 btn-dark label-tiny tracking-wider text-[11px] font-bold">
                            ADD TO BAG
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-neutral-50 border border-dashed border-neutral-200">
                    <p class="label-tiny text-neutral-400">Tidak ada produk kopi yang sesuai dengan filter Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>

<!-- Drawer Overlay -->
<div id="filter-drawer-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 transition-opacity duration-300 hidden opacity-0" onclick="closeFilterDrawer()"></div>

<!-- Drawer Content -->
<div id="filter-drawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
    <!-- Header -->
    <div class="p-6 border-b border-neutral-100 flex items-center justify-between">
        <div>
            <h2 class="font-display text-2xl font-bold text-brand-dark">Filter & Sort</h2>
            <p class="text-xs text-neutral-400 mt-1 uppercase tracking-wider">{{ $products->count() }} Products</p>
        </div>
        <button onclick="closeFilterDrawer()" class="text-neutral-400 hover:text-brand-dark transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>
    </div>

    <!-- Form Content -->
    <form id="filter-form" action="{{ route('shop') }}" method="GET" class="flex-grow overflow-y-auto p-6 space-y-6">
        <!-- Sort By Dropdown -->
        <div>
            <label class="block text-xs uppercase tracking-wider font-bold text-neutral-500 mb-2">Sort By</label>
            <select name="sort" class="w-full py-3 px-4 outline-none text-xs uppercase bg-white border border-neutral-300 text-brand-dark focus:border-brand-accent">
                <option value="best_seller" {{ $defaultSort === 'best_seller' ? 'selected' : '' }}>Best Seller / Featured</option>
                <option value="newest" {{ $defaultSort === 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ $defaultSort === 'price_asc' ? 'selected' : '' }}>Harga: Termurah</option>
                <option value="price_desc" {{ $defaultSort === 'price_desc' ? 'selected' : '' }}>Harga: Termahal</option>
            </select>
        </div>

        <!-- Kategori Section (Collapsible) -->
        <div class="border-t border-neutral-100 pt-4">
            <button type="button" onclick="toggleSection('category-filters')" class="w-full flex items-center justify-between text-xs uppercase tracking-wider font-bold text-brand-dark py-2">
                <span>Kategori</span>
                <span id="category-filters-chevron" class="material-symbols-outlined text-sm transition-transform duration-200">expand_more</span>
            </button>
            <div id="category-filters" class="mt-3 space-y-2.5 pl-1 transition-all duration-300">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="category" value="ALL" {{ $defaultCategory === 'ALL' ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent border-neutral-300">
                    <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Semua Kopi</span>
                </label>
                @foreach ($categories as $category)
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="category" value="{{ $category->id }}" {{ (string)$defaultCategory === (string)$category->id ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent border-neutral-300">
                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Ukuran / Berat Section (Collapsible) -->
        <div class="border-t border-neutral-100 pt-4">
            <button type="button" onclick="toggleSection('weight-filters')" class="w-full flex items-center justify-between text-xs uppercase tracking-wider font-bold text-brand-dark py-2">
                <span>Ukuran / Berat</span>
                <span id="weight-filters-chevron" class="material-symbols-outlined text-sm transition-transform duration-200">expand_more</span>
            </button>
            <div id="weight-filters" class="mt-3 space-y-2.5 pl-1 transition-all duration-300">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="weight" value="ALL" {{ $defaultWeight === 'ALL' ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent border-neutral-300">
                    <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">Semua Ukuran</span>
                </label>
                @foreach (['100g', '200g', '250g', '500g', '1kg'] as $wOption)
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="weight" value="{{ $wOption }}" {{ $defaultWeight === $wOption ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent border-neutral-300">
                        <span class="text-xs font-semibold text-neutral-600 uppercase tracking-wider">{{ $wOption }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </form>

    <!-- Sticky Drawer Footer -->
    <div class="p-6 border-t border-neutral-100 bg-white space-y-4">
        <button type="submit" form="filter-form" class="w-full py-4 bg-brand-dark hover:bg-brand-accent text-white font-bold text-xs uppercase tracking-widest transition-all rounded-full">
            Apply Filters
        </button>
        <div class="text-center">
            <a href="{{ route('shop') }}" class="text-xs font-semibold text-neutral-500 hover:text-brand-dark transition-colors underline uppercase tracking-wider">
                Remove all filters
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openFilterDrawer() {
        const overlay = document.getElementById('filter-drawer-overlay');
        const drawer = document.getElementById('filter-drawer');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
        }, 10);
    }

    function closeFilterDrawer() {
        const overlay = document.getElementById('filter-drawer-overlay');
        const drawer = document.getElementById('filter-drawer');
        overlay.classList.add('opacity-0');
        drawer.classList.add('translate-x-full');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }

    function toggleSection(id) {
        const section = document.getElementById(id);
        const chevron = document.getElementById(id + '-chevron');
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            chevron.classList.remove('rotate-180');
        } else {
            section.classList.add('hidden');
            chevron.classList.add('rotate-180');
        }
    }

    // Close drawer on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeFilterDrawer();
    });
</script>
@endsection
