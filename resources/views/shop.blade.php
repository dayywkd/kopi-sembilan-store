@extends('layouts.app')

@section('title', 'Shop | Toko Kopi Sembilan')

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
    .order-row {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .order-row:hover {
        background-color: #F9F9F9;
        color: #121212;
    }
    .rigid-border {
        border: 1px solid rgba(249, 249, 249, 0.1);
        transition: border-color 0.3s ease;
    }
    .rigid-border-t {
        border-top: 1px solid rgba(249, 249, 249, 0.1);
    }
    .rigid-border-b {
        border-bottom: 1px solid rgba(249, 249, 249, 0.1);
    }
    .product-card {
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .product-card:hover {
        border-color: rgba(249, 249, 249, 0.4);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
        transform: translateY(-4px);
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    select {
        background-image: none !important;
    }
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(24px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection

@section('content')
<main class="pt-32 flex min-h-screen">
    <!-- SideNavBar (Filter Shell) -->
    <aside class="hidden lg:flex flex-col sticky top-32 h-[calc(100vh-128px)] px-margin-desktop gap-stack-md border-r border-[#F9F9F9]/10 w-80 overflow-y-auto no-scrollbar animate-fade-in-up" style="animation-delay: 100ms;">
        <div class="mb-stack-lg">
            <h3 class="font-label-caps text-label-caps tracking-widest text-on-background mb-unit text-xs font-bold">CATEGORIES</h3>
            <p class="text-[10px] uppercase tracking-widest text-on-surface-variant mb-stack-md opacity-60">FILTER BY COLLECTION</p>
            
            <ul id="category-list" class="flex flex-col gap-unit font-label-caps text-label-caps tracking-widest text-xs">
                <!-- ALL COFFEE -->
                <li class="category-item cursor-pointer py-2 whitespace-nowrap {{ $defaultCategory === 'ALL' ? 'text-on-background font-bold underline underline-offset-8' : 'text-on-surface-variant hover:text-on-background hover:translate-x-1 transition-all' }}" data-category="ALL">
                    ALL COFFEE
                </li>
                @foreach ($categories as $category)
                    <!-- Dynamic Categories -->
                    <li class="category-item cursor-pointer py-2 whitespace-nowrap {{ $defaultCategory === $category->slug ? 'text-on-background font-bold underline underline-offset-8' : 'text-on-surface-variant hover:text-on-background hover:translate-x-1 transition-all' }}" data-category="{{ $category->name }}">
                        {{ $category->name }}
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="mb-stack-lg">
            <h3 class="font-label-caps text-label-caps tracking-widest text-on-background mb-stack-md text-xs font-bold">ROAST LEVEL</h3>
            <div class="flex flex-col gap-stack-sm text-xs text-on-surface-variant">
                <div class="py-1 uppercase">Light</div>
                <div class="py-1 uppercase">Medium-Light</div>
                <div class="py-1 uppercase">Medium</div>
                <div class="py-1 uppercase">Medium-Dark</div>
            </div>
        </div>
    </aside>

    <!-- Main Product Grid -->
    <section class="flex-1 px-margin-mobile md:px-margin-desktop py-stack-md">
        <!-- Breadcrumbs & Sort Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-stack-lg rigid-border-b pb-stack-md animate-fade-in-up" style="animation-delay: 50ms;">
            <div class="flex items-baseline gap-2">
                <h1 class="font-headline-lg text-headline-lg text-3xl font-display uppercase italic">OUR COFFEE</h1>
                <span id="results-count" class="hidden md:inline font-label-caps text-label-caps text-on-surface-variant text-xs ml-stack-md opacity-60">/ {{ $products->count() }} RESULTS</span>
            </div>
            
            <!-- Search Bar -->
            <div class="relative w-full lg:max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-on-surface-variant/60 text-lg">search</span>
                </span>
                <input type="text" id="search-input" placeholder="SEARCH COFFEE..." class="w-full bg-transparent border border-[#F9F9F9]/10 pl-10 pr-4 py-2 font-label-caps text-xs tracking-wider text-on-background placeholder:text-on-surface-variant/40 focus:outline-none focus:border-on-background focus:ring-0 transition-colors" />
            </div>
            
            <!-- Sort Dropdown -->
            <div class="flex items-center gap-stack-md justify-between lg:justify-end">
                <span class="font-label-caps text-label-caps text-on-surface-variant text-xs opacity-60">SORT BY:</span>
                <select id="sort-select" class="bg-transparent border-none font-label-caps text-xs text-on-background focus:ring-0 cursor-pointer appearance-none">
                    <option class="bg-background text-on-background" value="newest">NEWEST</option>
                    <option class="bg-background text-on-background" value="price-low-high">PRICE LOW-HIGH</option>
                    <option class="bg-background text-on-background" value="alphabetical">ALPHABETICAL</option>
                </select>
            </div>
        </div>

        <!-- Grid -->
        <div id="product-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @forelse ($products as $index => $product)
                <!-- Product Card -->
                <div class="rigid-border group flex flex-col bg-surface-container-lowest product-card p-6 animate-fade-in-up" 
                     style="animation-delay: {{ 150 + ($index * 50) }}ms;"
                     data-name="{{ $product->name }}" 
                     data-category="{{ $product->category->name }} • {{ $product->roast_level }}" 
                     data-price="{{ (int)$product->price }}" 
                     data-date="{{ $index + 1 }}" 
                     data-collection="{{ $product->category->name }}">
                    
                    <div class="relative aspect-[4/5] overflow-hidden bg-[#1a1a1a] mb-6">
                        <img alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 grayscale" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8VV0074LU9iw6f5wVnCpg4qzJqHNeseWpVVjqbmkDPmv0jvlt5cO4M0-OHxXvotDuZBsjPgu_veF_GNR39-ElX3KTw34lpdpCjU9ZeDhgpnuMuoQGWDmKnOympxZcy8aXJ-77djRB7CXEfNAsrxu82pLZ1dmNwbNIRmakbm1lvahQehEw3uKh3cdNBjy0ZEB2txi3h1-cXrhLcxjRJzxzGyed10xBH05kTNFqcDfdl1rqg3oabxY-dsSNAQyJgfkhWi4jbjyaVb8"/>
                    </div>
                    
                    <div class="flex-grow flex flex-col justify-between gap-4">
                        <div>
                            <span class="font-label-caps text-on-surface-variant uppercase tracking-widest text-[10px] opacity-60">{{ $product->category->name }} • {{ $product->roast_level }}</span>
                            <h4 class="font-headline-md text-xl font-display text-on-background uppercase tracking-tight mt-1">{{ $product->name }}</h4>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="flex flex-col gap-1">
                                <span class="font-label-caps text-on-background font-bold text-sm">RP. {{ number_format($product->price, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-1.5">
                                    <div class="flex text-yellow-400">
                                        @php $rating = round($product->average_rating); @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-[11px] {{ $i <= $rating ? 'text-yellow-400' : 'text-[#F9F9F9]/20' }}" style="font-variation-settings: 'FILL' {{ $i <= $rating ? 1 : 0 }}, 'wght' 200, 'GRAD' 0, 'opsz' 24">star</span>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-neutral-400 font-sans">({{ $product->reviews_count }})</span>
                                </div>
                            </div>
                            @if ($product->status === 'SOLD OUT')
                                <span class="font-label-caps text-red-500 text-xs font-bold">SOLD OUT</span>
                            @else
                                <a class="font-label-caps text-on-surface-variant underline underline-offset-4 hover:text-on-background text-xs transition-colors" href="{{ route('product.show', $product->slug) }}">VIEW</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Handled dynamically by JS below -->
            @endforelse
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    const searchInput = document.getElementById('search-input');
    const sortSelect = document.getElementById('sort-select');
    const productGrid = document.getElementById('product-grid');
    const resultsCount = document.getElementById('results-count');
    const categoryItems = document.querySelectorAll('.category-item');
    
    // Menyimpan data card original
    const originalCards = Array.from(document.querySelectorAll('.product-card'));
    
    // Inisialisasi Kategori Aktif dari route default Laravel
    @php
        $activeCatName = 'ALL';
        if ($defaultCategory !== 'ALL') {
            $cat = $categories->where('slug', $defaultCategory)->first();
            if ($cat) {
                $activeCatName = $cat->name;
            }
        }
    @endphp
    let activeCategory = '{{ $activeCatName }}';
    
    // Tambah pesan "No results"
    let noResultsMessage = document.getElementById('no-results-message');
    if (!noResultsMessage) {
        noResultsMessage = document.createElement('div');
        noResultsMessage.id = 'no-results-message';
        noResultsMessage.className = 'col-span-full py-16 text-center text-on-surface-variant font-label-caps text-xs tracking-widest hidden';
        noResultsMessage.innerHTML = 'NO PRODUCTS FOUND';
        productGrid.appendChild(noResultsMessage);
    }
    
    function filterAndSort() {
        const query = searchInput.value.toLowerCase().trim();
        const sortBy = sortSelect.value;
        
        let visibleCards = originalCards.filter(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const categoryText = card.getAttribute('data-category').toLowerCase();
            const cardCollection = card.getAttribute('data-collection') || '';
            
            const matchesSearch = name.includes(query) || categoryText.includes(query);
            const matchesCategory = activeCategory === 'ALL' || cardCollection === activeCategory;
            const matches = matchesSearch && matchesCategory;
            
            if (matches) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
            return matches;
        });
        
        // Sortir
        if (sortBy === 'newest') {
            visibleCards.sort((a, b) => parseInt(a.getAttribute('data-date')) - parseInt(b.getAttribute('data-date')));
        } else if (sortBy === 'price-low-high') {
            visibleCards.sort((a, b) => parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price')));
        } else if (sortBy === 'alphabetical') {
            visibleCards.sort((a, b) => a.getAttribute('data-name').localeCompare(b.getAttribute('data-name')));
        }
        
        // Render ulang ke grid
        visibleCards.forEach(card => {
            productGrid.appendChild(card);
        });
        
        // Update hitung hasil
        resultsCount.textContent = `/ ${visibleCards.length} RESULT${visibleCards.length !== 1 ? 'S' : ''}`;
        
        // Tampilkan/sembunyikan pesan kosong
        if (visibleCards.length === 0) {
            noResultsMessage.classList.remove('hidden');
        } else {
            noResultsMessage.classList.add('hidden');
        }
    }
    
    if (searchInput && sortSelect && productGrid && resultsCount) {
        searchInput.addEventListener('input', filterAndSort);
        sortSelect.addEventListener('change', filterAndSort);
        
        // Click handler untuk Kategori Sidebar
        categoryItems.forEach(item => {
            item.addEventListener('click', () => {
                categoryItems.forEach(el => {
                    el.className = 'category-item cursor-pointer py-2 whitespace-nowrap text-on-surface-variant hover:text-on-background hover:translate-x-1 transition-all';
                });
                item.className = 'category-item cursor-pointer py-2 whitespace-nowrap text-on-background font-bold underline underline-offset-8';
                
                activeCategory = item.getAttribute('data-category');
                filterAndSort();
            });
        });
        
        // Jalankan filter pertama kali
        filterAndSort();
    }
</script>
@endsection
