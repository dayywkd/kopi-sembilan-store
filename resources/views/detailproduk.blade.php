@extends('layouts.app')

@section('title', $product->name . ' | Toko Kopi Sembilan')

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
    .stark-input {
        background: transparent !important;
        border: 1px solid #444444 !important;
        color: #F9F9F9 !important;
        border-radius: 0px !important;
    }
    .stark-input:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #F9F9F9 !important;
    }
    select {
        background-image: none !important;
    }
</style>
@endsection

@section('content')
<main class="pt-32 min-h-screen flex flex-col justify-between">
    <section class="grid grid-cols-1 lg:grid-cols-2 flex-grow">
        <!-- Left Side: Product Gallery -->
        <div class="relative bg-[#1a1a1a]/20 border-r border-[#F9F9F9]/10 flex items-center justify-center p-margin-desktop group overflow-hidden min-h-[400px]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(249,249,249,0.03)_0%,transparent_70%)] pointer-events-none"></div>
            <div class="w-full h-full flex items-center justify-center transition-all duration-700">
                <img alt="{{ $product->name }}" class="max-w-full max-h-[80%] object-contain transition-transform duration-1000 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105 grayscale" src="https://lh3.googleusercontent.com/aida/AP1WRLsGnmckoSHSLe-DwOGsM6GmqQ5-_5i3etbFi2klrcBPyscEY_rjBMrryflSBZqdNeqwIQDsbl667aVTg-I9A3gq6AcwMti-D9ry52pa4e7dENL3iWKcRZGNZjmOyTHikIXVlPDsoPmGXbwYWqXAEklbq7eGo98p99QqfeGAFPm7t3uQ0AvOvpjXkEM3-Kqqf5La7THN_tBp7zUuPQiigpZM4VIgrGtG-ZA_079iNLWBPCyjHcAY_pfezQ"/>
            </div>
            <!-- Navigation Arrows (Statis) -->
            <div class="absolute inset-0 flex items-center justify-between px-6 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="bg-[#121212]/80 backdrop-blur-md border border-[#F9F9F9]/20 p-2 hover:bg-[#F9F9F9] hover:text-[#121212] transition-colors">
                    <span class="material-symbols-outlined text-sm leading-none flex">chevron_left</span>
                </button>
                <button class="bg-[#121212]/80 backdrop-blur-md border border-[#F9F9F9]/20 p-2 hover:bg-[#F9F9F9] hover:text-[#121212] transition-colors">
                    <span class="material-symbols-outlined text-sm leading-none flex">chevron_right</span>
                </button>
            </div>
        </div>

        <!-- Right Side: Product Details -->
        <div class="flex flex-col justify-between p-margin-mobile md:p-margin-desktop bg-background">
            <div class="space-y-stack-xl">
                <!-- Title Section -->
                <div class="space-y-4">
                    <span class="font-label-caps text-label-caps uppercase tracking-[0.2em] opacity-60 text-xs block">{{ $product->category->name }} SERIES</span>
                    <h1 class="font-serif-italic italic text-4xl md:text-5xl font-display text-on-background leading-tight">
                        {{ $product->name }}
                    </h1>
                    <div class="pt-2 flex items-center gap-4">
                        <span id="product-price-display" class="font-headline-md text-2xl font-display font-semibold text-neutral-200">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <a href="#reviews-section" class="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                            <div class="flex text-yellow-400">
                                @php $rating = round($product->average_rating); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-[14px] {{ $i <= $rating ? 'text-yellow-400' : 'text-[#F9F9F9]/20' }}" style="font-variation-settings: 'FILL' {{ $i <= $rating ? 1 : 0 }}, 'wght' 200, 'GRAD' 0, 'opsz' 24">star</span>
                                @endfor
                            </div>
                            <span class="text-xs text-neutral-400 font-sans">({{ $product->reviews_count }})</span>
                        </a>
                    </div>
                </div>
                
                <!-- Metadata Grid -->
                <div class="grid grid-cols-2 gap-y-stack-lg gap-x-gutter pt-stack-lg border-t border-[#F9F9F9]/10">
                    <div class="space-y-unit">
                        <p class="font-label-caps text-xs opacity-60">ROAST LEVEL</p>
                        <p class="font-sans font-light text-base text-neutral-200">{{ $product->roast_level }}</p>
                    </div>
                    <div class="space-y-unit">
                        <p class="font-label-caps text-xs opacity-60">ALTITUDE</p>
                        <p class="font-sans font-light text-base text-neutral-200">{{ $product->altitude }}</p>
                    </div>
                    <div class="col-span-2 space-y-unit mt-4">
                        <p class="font-label-caps text-xs opacity-60">SENSORY NOTES</p>
                        <p class="font-sans font-bold text-neutral-300 tracking-wide uppercase text-sm">{{ $product->sensory_notes }}</p>
                    </div>
                </div>
                
                <!-- Selection & Action -->
                <div class="space-y-gutter pt-6">
                    <div class="space-y-stack-sm flex flex-col gap-2">
                        <label class="font-label-caps text-xs opacity-60 uppercase font-semibold" for="grind">Size Beans</label>
                        
                        <!-- Select Asli yang disembunyikan agar logikanya tetap terhubung -->
                        @php
                            $availableSizes = $product->sizes ?? [['size' => '100gr', 'price' => $product->price]];
                        @endphp
                        <select class="hidden" id="grind">
                            @foreach ($availableSizes as $sizeOpt)
                                <option value="{{ $sizeOpt['size'] }}" data-price="{{ $sizeOpt['price'] }}">{{ $sizeOpt['size'] }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Custom Dropdown UI Premium -->
                        <div class="relative" id="custom-grind-dropdown">
                            <button type="button" id="custom-grind-trigger" class="stark-input w-full py-4 px-4 font-sans text-sm uppercase tracking-widest cursor-pointer flex justify-between items-center border border-[#444444] transition-all hover:border-[#F9F9F9]">
                                <span id="custom-grind-selected-text">@if(count($availableSizes) > 0){{ $availableSizes[0]['size'] }}@else 100gr @endif</span>
                                <span class="material-symbols-outlined transition-transform duration-300" id="custom-grind-arrow">expand_more</span>
                            </button>
                            
                            <ul id="custom-grind-options" class="absolute left-0 right-0 mt-2 bg-[#121212] border border-[#F9F9F9]/20 divide-y divide-[#F9F9F9]/10 z-20 hidden opacity-0 transition-all duration-200 max-h-60 overflow-y-auto no-scrollbar shadow-2xl">
                                @foreach ($availableSizes as $sizeOpt)
                                    <li class="custom-option py-4 px-4 hover:bg-[#F9F9F9] hover:text-[#121212] cursor-pointer font-sans text-sm uppercase tracking-widest transition-colors duration-150" 
                                        data-value="{{ $sizeOpt['size'] }}" 
                                        data-price="{{ $sizeOpt['price'] }}">
                                        {{ $sizeOpt['size'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    @if ($product->status === 'SOLD OUT')
                        <button disabled class="w-full bg-[#1a1a1a] text-[#444444] font-bold py-6 uppercase tracking-[0.25em] border border-[#444444] cursor-not-allowed">
                            SOLD OUT
                        </button>
                    @else
                        <button id="add-to-bag-btn" class="w-full bg-white text-black font-bold py-6 uppercase tracking-[0.25em] border border-white transition-all duration-300 hover:bg-transparent hover:text-white active:scale-[0.98]">
                            Add to Bag
                        </button>
                    @endif
                </div>
            </div>

            <!-- Product Story / Description -->
            <div class="mt-stack-xl space-y-stack-md pt-stack-lg border-t border-[#F9F9F9]/10">
                <p class="font-sans font-light text-sm text-neutral-300 max-w-lg leading-relaxed">
                    Ethically sourced and roasted in small batches to preserve its natural flavors. This selection represents our uncompromising commitment to precision coffee. We roast to highlight the optimal balance between body, sweetness, and the complex notes unique to its origin.
                </p>
                <div class="flex gap-8 text-xs font-semibold pt-4">
                    <a class="font-label-caps border-b border-[#F9F9F9]/10 pb-1 hover:opacity-50 transition-opacity" href="#">SOURCING DETAILS</a>
                    <a class="font-label-caps border-b border-[#F9F9F9]/10 pb-1 hover:opacity-50 transition-opacity" href="#">BREW GUIDE</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews-section" class="border-t border-[#F9F9F9]/10 py-16 px-margin-mobile md:px-margin-desktop bg-[#121212]">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-baseline border-b border-[#F9F9F9]/10 pb-6 mb-8">
                <h3 class="font-display text-3xl uppercase italic">Ulasan Pelanggan</h3>
                <span class="font-sans text-xs text-neutral-400">Total Ulasan: {{ $product->reviews_count }}</span>
            </div>
            
            @if ($product->reviews->isEmpty())
                <p class="text-neutral-500 font-sans text-sm italic">Belum ada ulasan untuk produk ini. Ulasan baru dapat ditulis setelah Anda membeli produk ini dan melakukan konfirmasi penerimaan pesanan pada halaman pelacakan.</p>
            @else
                <div class="space-y-6">
                    @foreach ($product->reviews as $review)
                        <div class="border border-[#F9F9F9]/10 p-6 bg-[#1a1a1a]/25">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-bold text-sm text-[#F9F9F9]">{{ $review->customer_name }}</h5>
                                    <span class="text-[10px] text-neutral-500 font-sans">{{ $review->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-yellow-400' : 'text-[#F9F9F9]/20' }}" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? 1 : 0 }}, 'wght' 200, 'GRAD' 0, 'opsz' 24">star</span>
                                    @endfor
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="text-xs text-neutral-300 font-sans leading-relaxed">"{{ $review->comment }}"</p>
                            @else
                                <p class="text-xs text-neutral-500 font-sans italic">Hanya memberikan rating bintang.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Product Features Section -->
    <section class="border-t border-[#F9F9F9]/10 bg-background">
        <div class="grid grid-cols-1 md:grid-cols-3 border-b border-[#F9F9F9]/10">
            <div class="p-margin-desktop border-b md:border-b-0 md:border-r border-[#F9F9F9]/10 group overflow-hidden relative h-[400px]">
                <div class="absolute inset-0 z-0">
                    <img class="w-full h-full object-cover grayscale opacity-30 group-hover:scale-110 transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9I_ZdRntUcgejY7rKZpKCmoA_i2GT7CMhP7glqHBB4XDkoRMNpoeTNAaq40WzNnVHX5al7W-DaqQDEWNWmLX06eA_lYPfqV-FvuoEqSjzS7QDf_Md-7Ay4bTjIVrXCdpU-vJEJqMcJVuUQfardmZxDnG3tU2UxyjdI6XFIhaiLpMuNxDQaPaqMMZ9pOxyM1TPibFrgqUrfFAvuv1b0OWHBPwntYbkYtFLYJ62yh0OTrYt3-BOU05p5Hzt7ZQil9SDxG8dnhAYMF0"/>
                </div>
                <div class="relative z-10 h-full flex flex-col justify-end p-8">
                    <h3 class="font-display text-xl uppercase italic mb-2">PRECISION ROASTING</h3>
                    <p class="font-sans font-light text-neutral-300 text-sm">Controlled small-batch roasting ensures absolute flavor consistency.</p>
                </div>
            </div>
            <div class="p-margin-desktop border-b md:border-b-0 md:border-r border-[#F9F9F9]/10 flex flex-col justify-center items-center text-center p-8 space-y-4 min-h-[300px]">
                <span class="material-symbols-outlined text-5xl opacity-80" style="font-variation-settings: 'wght' 200;">eco</span>
                <h3 class="font-display text-xl uppercase italic">REGENERATIVE</h3>
                <p class="font-sans font-light text-neutral-300 text-sm">Sourced via regenerative agriculture practices that support local soil health.</p>
            </div>
            <div class="p-margin-desktop flex flex-col justify-between p-8 min-h-[300px]">
                <div>
                    <h3 class="font-display text-xl uppercase italic">TERROIR</h3>
                    <p class="font-sans font-light text-neutral-300 text-sm mt-2">Single-origin from volcanic soil rich in nutrients.</p>
                </div>
                <div class="pt-6">
                    <div class="flex justify-between items-center py-2 border-b border-[#F9F9F9]/10 text-xs">
                        <span class="font-label-caps opacity-60">SOIL TYPE</span>
                        <span class="font-sans font-light text-neutral-200">Andosol</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Custom Dropdown JS Handler
        const dropdownTrigger = document.getElementById('custom-grind-trigger');
        const dropdownOptions = document.getElementById('custom-grind-options');
        const dropdownArrow = document.getElementById('custom-grind-arrow');
        const selectedText = document.getElementById('custom-grind-selected-text');
        const nativeSelect = document.getElementById('grind');
        const optionItems = document.querySelectorAll('.custom-option');
        const priceDisplay = document.getElementById('product-price-display');

        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        if (dropdownTrigger && dropdownOptions) {
            dropdownTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = dropdownOptions.classList.contains('hidden');
                if (isHidden) {
                    dropdownOptions.classList.remove('hidden');
                    dropdownArrow.classList.add('rotate-180');
                    setTimeout(() => {
                        dropdownOptions.classList.remove('opacity-0');
                        dropdownOptions.classList.add('opacity-100');
                    }, 10);
                } else {
                    closeDropdown();
                }
            });

            function closeDropdown() {
                dropdownOptions.classList.remove('opacity-100');
                dropdownOptions.classList.add('opacity-0');
                dropdownArrow.classList.remove('rotate-180');
                setTimeout(() => {
                    dropdownOptions.classList.add('hidden');
                }, 200);
            }

            optionItems.forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    const price = parseInt(item.getAttribute('data-price')) || {{ (int)$product->price }};
                    
                    selectedText.innerText = value;
                    if (nativeSelect) {
                        nativeSelect.value = value;
                    }
                    
                    // Update tampilan harga secara dinamis
                    if (priceDisplay) {
                        priceDisplay.innerText = formatRupiah(price);
                    }
                    
                    closeDropdown();
                });
            });

            // Klik di luar dropdown untuk menutup
            document.addEventListener('click', (e) => {
                if (!dropdownOptions.classList.contains('hidden') && !e.target.closest('#custom-grind-dropdown')) {
                    closeDropdown();
                }
            });
        }

        const addToBagBtn = document.getElementById('add-to-bag-btn');
        if (addToBagBtn) {
            addToBagBtn.addEventListener('click', () => {
                const grindSize = nativeSelect ? nativeSelect.value : '100gr';
                
                // Ambil harga yang sesuai dengan ukuran yang dipilih
                const selectedOption = dropdownOptions.querySelector(`[data-value="${grindSize}"]`);
                const currentPrice = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) : {{ (int)$product->price }};

                let cart = getCart();
                let existingItem = cart.find(item => item.id === {{ $product->id }} && item.grind_size === grindSize);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: {{ $product->id }},
                        name: "{{ $product->name }}",
                        price: currentPrice,
                        grind_size: grindSize,
                        quantity: 1,
                        image: "https://lh3.googleusercontent.com/aida/AP1WRLsGnmckoSHSLe-DwOGsM6GmqQ5-_5i3etbFi2klrcBPyscEY_rjBMrryflSBZqdNeqwIQDsbl667aVTg-I9A3gq6AcwMti-D9ry52pa4e7dENL3iWKcRZGNZjmOyTHikIXVlPDsoPmGXbwYWqXAEklbq7eGo98p99QqfeGAFPm7t3uQ0AvOvpjXkEM3-Kqqf5La7THN_tBp7zUuPQiigpZM4VIgrGtG-ZA_079iNLWBPCyjHcAY_pfezQ"
                    });
                }
                
                saveCart(cart);
                showToast(`"${grindSize} - {{ $product->name }}" ditambahkan.`);
            });
        }

        // Efek klik tombol mikro
        document.querySelectorAll('button, a').forEach(el => {
            el.addEventListener('mousedown', () => {
                el.classList.add('scale-95');
                setTimeout(() => el.classList.remove('scale-95'), 100);
            });
        });
    });
</script>
@endsection
