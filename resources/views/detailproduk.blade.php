@extends('layouts.app')

@section('title', $product->name . ' | Toko Kopi Sembilan')
@section('meta_description', __trans('Beli ' . $product->name . ' - Roast Level: ' . $product->roast_level . ', Altitude: ' . $product->altitude . '. Notes: ' . $product->sensory_notes . '. Dapatkan biji kopi pilihan berkualitas tinggi dari Toko Kopi Sembilan.', 'Buy ' . $product->name . ' - Roast Level: ' . $product->roast_level . ', Altitude: ' . $product->altitude . '. Notes: ' . $product->sensory_notes . '. Get premium selected coffee beans from Toko Kopi Sembilan.'))
@section('meta_image', $product->image_url)

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
        background: #FFFFFF !important;
        border: 1px solid #E5E7EB !important;
        color: #121212 !important;
        border-radius: 0px !important;
    }
    .stark-input:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #121212 !important;
    }
    select {
        background-image: none !important;
    }
    .size-pill {
        border-radius: 0px !important;
    }
    .gallery-thumb {
        border-radius: 0px !important;
    }
</style>
@endsection

@section('content')
<main class="pt-32 min-h-screen flex flex-col justify-between bg-white">
    <section class="grid grid-cols-1 lg:grid-cols-12 flex-grow">
        <!-- Left Side: Product Gallery & Thumbnails (60% Desktop Width) -->
        <div class="col-span-12 lg:col-span-7 bg-white flex flex-col justify-center gap-6 p-8 md:p-12 relative group min-h-[450px] items-center">
            
            <!-- Back to Shop Link -->
            <a href="{{ route('shop') }}" class="absolute top-6 left-6 z-10 flex items-center gap-2 text-neutral-400 hover:text-[#121212] transition-colors text-[10px] sm:text-xs font-sans font-bold tracking-widest uppercase">
                <span class="material-symbols-outlined text-[16px] sm:text-[18px]">arrow_back</span>
                {{ __trans('Kembali ke Katalog', 'Back to Catalog') }}
            </a>

            <!-- Main Image Container -->
            <div class="w-full h-[350px] lg:h-[450px] flex items-center justify-center relative">
                @if($product->image_path)
                    <img id="main-product-image" alt="{{ $product->name }}" class="max-w-[80%] max-h-[80%] object-contain transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-[1.02]" src="{{ $product->image_url }}"/>
                @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-300 text-xs tracking-widest font-mono">NO IMAGE</div>
                @endif

                <!-- Navigation Arrows -->
                <div class="absolute inset-0 flex items-center justify-between px-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <button type="button" onclick="prevImage()" class="bg-[#FFFFFF]/80 backdrop-blur-md border border-[#E5E7EB] p-2 hover:bg-[#121212] hover:text-[#FFFFFF] transition-colors text-[#121212] pointer-events-auto cursor-pointer">
                        <span class="material-symbols-outlined text-sm leading-none flex">chevron_left</span>
                    </button>
                    <button type="button" onclick="nextImage()" class="bg-[#FFFFFF]/80 backdrop-blur-md border border-[#E5E7EB] p-2 hover:bg-[#121212] hover:text-[#FFFFFF] transition-colors text-[#121212] pointer-events-auto cursor-pointer">
                        <span class="material-symbols-outlined text-sm leading-none flex">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Thumbnail strip -->
            @php
                $galleryImages = array_values(array_filter(array_merge(
                    [$product->image_path ? $product->image_url : null],
                    $product->images->map(fn ($image) => $image->image_url)->all()
                )));
            @endphp
            
            <div class="flex flex-row gap-3 w-full justify-center mt-4 flex-wrap">
                @foreach($galleryImages as $index => $imgUrl)
                    <button type="button" 
                            class="gallery-thumb w-16 h-20 bg-white border {{ $index === 0 ? 'border-[#121212] border-2' : 'border-neutral-200' }} p-1 hover:border-[#121212] transition-all duration-150 flex-shrink-0"
                            onclick="changeActiveImage(this, '{{ $imgUrl }}', {{ $index }})">
                        <img class="w-full h-full object-cover" src="{{ $imgUrl }}" alt="Gallery thumbnail {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Right Side: Product Details (40% Desktop Width) -->
        <div class="col-span-12 lg:col-span-5 flex flex-col justify-between p-12 md:p-16 lg:p-20 bg-white">
            <div class="space-y-12">
                <!-- Title Section -->
                <div class="space-y-4">
                    <span class="font-label-caps text-label-caps uppercase tracking-[0.2em] text-neutral-400 text-xs block font-bold">{{ $product->category->name }} SERIES</span>
                    <h1 class="font-serif-italic italic text-4xl md:text-5xl font-display text-[#121212] leading-tight">
                        {{ $product->name }}
                    </h1>
                    <div class="pt-2 flex items-center gap-4">
                        <span id="product-price-display" class="font-headline-md text-2xl font-display font-semibold text-[#121212]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <a href="#reviews-section" class="flex items-center gap-1.5 hover:opacity-80 transition-opacity">
                            <div class="flex text-brand-accent">
                                @php $rating = round($product->average_rating); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-[14px] {{ $i <= $rating ? 'text-brand-accent' : 'text-neutral-300' }}" style="font-variation-settings: 'FILL' {{ $i <= $rating ? 1 : 0 }}, 'wght' 200, 'GRAD' 0, 'opsz' 24">star</span>
                                @endfor
                            </div>
                            <span class="text-xs text-neutral-500 font-sans">({{ $product->reviews_count }})</span>
                        </a>
                    </div>
                </div>

                <!-- Intro Description -->
                <p class="font-sans font-light text-sm text-neutral-600 leading-relaxed">
                    {!! __trans('Menghadirkan keunikan rasa dengan dominasi aroma <strong class="text-neutral-900 font-semibold">' . strtolower($product->sensory_notes) . '</strong>. Biji kopi pilihan ini dipanggang dengan presisi tinggi di roastery kami untuk mengeluarkan keseimbangan rasa yang optimal.', 'Delivering a unique flavor experience dominated by sensory notes of <strong class="text-neutral-900 font-semibold">' . strtolower($product->sensory_notes) . '</strong>. These selected coffee beans are precision roasted in our roastery to unlock their optimal flavor balance.') !!}
                </p>
                
                <!-- Selection & Action -->
                <div class="space-y-6 pt-2">
                    <div class="space-y-3">
                        <label class="font-label-caps text-xs text-neutral-500 uppercase font-semibold block" for="grind">{{ __trans('Ukuran Biji Kopi', 'Size Beans') }}</label>
                        
                        <!-- Select Asli yang disembunyikan agar logikanya tetap terhubung -->
                        @php
                            $availableSizes = $product->sizes ?? [['size' => '100gr', 'price' => $product->price]];
                        @endphp
                        <select class="hidden" id="grind">
                            @foreach ($availableSizes as $sizeOpt)
                                <option value="{{ $sizeOpt['size'] }}" data-price="{{ $sizeOpt['price'] }}">{{ \App\Models\Product::formatSize($sizeOpt['size']) }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Horizontal Pill Selectors -->
                        <div class="flex flex-wrap gap-2.5" id="size-pill-container">
                            @foreach ($availableSizes as $index => $sizeOpt)
                                <button type="button" 
                                        class="size-pill px-6 py-3 border text-[11px] font-sans font-bold uppercase tracking-widest transition-all duration-150 {{ $index === 0 ? 'bg-[#121212] text-white border-[#121212]' : 'bg-white text-[#121212] border-neutral-200 hover:border-[#121212]' }}"
                                        data-value="{{ $sizeOpt['size'] }}" 
                                        data-price="{{ $sizeOpt['price'] }}">
                                    {{ \App\Models\Product::formatSize($sizeOpt['size']) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Action Button -->
                    @if ($product->status === 'SOLD OUT')
                        <button disabled class="w-full bg-neutral-100 text-neutral-400 font-bold py-5 uppercase tracking-[0.25em] border border-neutral-200 cursor-not-allowed text-xs">
                            {{ __trans('HABIS', 'SOLD OUT') }}
                        </button>
                    @else
                        <button id="add-to-bag-btn" class="w-full bg-brand-dark text-white font-bold py-5 uppercase tracking-[0.25em] border border-brand-dark transition-all duration-300 hover:bg-[#222222] hover:border-[#222222] active:scale-[0.98] text-xs">
                            {{ __trans('Tambah ke Keranjang', 'Add to Bag') }}
                        </button>
                    @endif
                </div>

                <!-- Accordion Details -->
                <div class="border-t border-[#E5E7EB] divide-y divide-[#E5E7EB]">
                    <!-- Accordion 01: Coffee Profile -->
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger w-full py-4 flex justify-between items-center text-left font-sans text-[11px] font-bold uppercase tracking-widest text-[#121212] focus:outline-none">
                            <span>{{ __trans('01 / Profil Kopi', '01 / Coffee Profile') }}</span>
                            <span class="material-symbols-outlined text-sm leading-none transition-transform duration-300">add</span>
                        </button>
                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="pb-6 pt-2 space-y-4 text-xs font-sans text-neutral-600 uppercase tracking-wider">
                                <div class="flex justify-between py-1 border-b border-[#F3F4F6]">
                                    <span class="opacity-60">{{ __trans('Kategori', 'Category') }}</span>
                                    <span class="font-bold text-[#121212]">{{ $product->category->name }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-[#F3F4F6]">
                                    <span class="opacity-60">{{ __trans('Tingkat Roasting', 'Roast Level') }}</span>
                                    <span class="font-bold text-[#121212]">{{ $product->roast_level }}</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-[#F3F4F6]">
                                    <span class="opacity-60">{{ __trans('Ketinggian', 'Altitude') }}</span>
                                    <span class="font-bold text-[#121212]">{{ $product->altitude }}</span>
                                </div>
                                <div class="flex flex-col gap-2 pt-2">
                                    <span class="opacity-60">{{ __trans('Catatan Rasa', 'Sensory Notes') }}</span>
                                    <span class="font-bold text-sm text-[#121212] normal-case tracking-normal italic">"{{ $product->sensory_notes }}"</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion 02: Brew Guide -->
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger w-full py-4 flex justify-between items-center text-left font-sans text-[11px] font-bold uppercase tracking-widest text-[#121212] focus:outline-none">
                            <span>{{ __trans('02 / Panduan Seduh', '02 / Brew Guide') }}</span>
                            <span class="material-symbols-outlined text-sm leading-none transition-transform duration-300">add</span>
                        </button>
                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="pb-6 pt-2 space-y-4 text-xs font-sans text-neutral-600 leading-relaxed text-[10px] uppercase tracking-wider">
                                <p class="font-bold text-[#121212] mb-2">{{ __trans('Rekomendasi: Pour Over (V60)', 'Recommended: Pour Over (V60)') }}</p>
                                <ul class="list-disc pl-4 space-y-1.5">
                                    <li>{{ __trans('Rasio: 1:15 (15g kopi ke 225g air)', 'Ratio: 1:15 (15g coffee to 225g water)') }}</li>
                                    <li>{{ __trans('Tingkat Gilingan: Sedang-Halus (seperti garam meja)', 'Grind Size: Medium-Fine (like table salt)') }}</li>
                                    <li>{{ __trans('Suhu Air: 92°C - 94°C', 'Water Temp: 92°C - 94°C') }}</li>
                                    <li>{{ __trans('Waktu Seduh: 2:30 - 3:00 menit', 'Pour Time: 2:30 - 3:00 mins') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion 03: Sourcing & Logistics -->
                    <div class="accordion-item">
                        <button type="button" class="accordion-trigger w-full py-4 flex justify-between items-center text-left font-sans text-[11px] font-bold uppercase tracking-widest text-[#121212] focus:outline-none">
                            <span>{{ __trans('03 / Asal-usul & Logistik', '03 / Sourcing & Logistics') }}</span>
                            <span class="material-symbols-outlined text-sm leading-none transition-transform duration-300">add</span>
                        </button>
                        <div class="accordion-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="pb-6 pt-2 space-y-3 text-xs font-sans text-neutral-500 leading-relaxed uppercase tracking-wider text-[10px]">
                                <p>{{ __trans('Diperoleh secara etis dan perdagangan langsung. Kami membayar harga premium langsung kepada petani untuk memastikan praktik produksi yang berkelanjutan dan berkualitas tinggi.', 'Ethically sourced and direct trade. We pay premium prices directly to farmers to ensure sustainable and high-quality production practices.') }}</p>
                                <p>{{ __trans('Tanggal Roasting: Semua pesanan dijamin di-roasting dalam waktu 7 hari sebelum pengiriman untuk memastikan kesegaran puncak dan keutuhan rasa.', 'Roast Date: All orders are guaranteed roasted within 7 days of shipment to ensure peak freshness and flavor integrity.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="reviews-section" class="border-t border-[#E5E7EB] py-20 px-margin-mobile md:px-margin-desktop bg-white">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-baseline border-b border-[#E5E7EB] pb-6 mb-8">
                <h3 class="font-display text-3xl uppercase italic text-[#121212]">{{ __trans('Ulasan Pelanggan', 'Customer Reviews') }}</h3>
                <span class="font-sans text-xs text-neutral-500">{{ __trans('Total Ulasan', 'Total Reviews') }}: {{ $product->reviews_count }}</span>
            </div>
            
            @if ($product->reviews->isEmpty())
                <p class="text-neutral-500 font-sans text-sm italic">{{ __trans('Belum ada ulasan untuk produk ini.', 'No reviews for this product yet.') }}</p>
            @else
                <div class="space-y-6">
                    @foreach ($product->reviews as $review)
                        <div class="border border-[#E5E7EB] p-6 bg-brand-cream">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h5 class="font-bold text-sm text-[#121212]">{{ $review->customer_name }}</h5>
                                    <span class="text-[10px] text-neutral-500 font-sans">{{ $review->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div class="flex text-brand-accent">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-brand-accent' : 'text-neutral-300' }}" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? 1 : 0 }}, 'wght' 200, 'GRAD' 0, 'opsz' 24">star</span>
                                    @endfor
                                </div>
                            </div>
                            @if ($review->comment)
                                <p class="text-xs text-neutral-700 font-sans leading-relaxed">"{{ $review->comment }}"</p>
                            @else
                                <p class="text-xs text-neutral-500 font-sans italic">{{ __trans('Hanya memberikan rating bintang.', 'Only left a rating.') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Product Features Section (Redesigned Editorial Style) -->
    <section class="border-t border-[#E5E7EB] py-24 px-margin-mobile md:px-margin-desktop bg-white">
        <div class="max-w-container-max mx-auto text-[#121212]">
            <div class="text-center mb-16">
                <span class="font-label-caps text-xs text-neutral-400 font-bold uppercase tracking-[0.2em]">{{ __trans('Keahlian Kami', 'Our Craft') }}</span>
                <h2 class="font-serif-italic italic text-3xl md:text-4xl font-display text-[#121212] mt-2">{{ __trans('Komitmen pada Kualitas', 'Commitment to Quality') }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Feature 1: Roasting -->
                <div class="group relative h-[380px] overflow-hidden flex flex-col justify-end p-8 border border-neutral-100 bg-[#121212]">
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-55" src="{{ asset('images/roasting_bg.jpg') }}" alt="Precision Roasting"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    </div>
                    <div class="relative z-10 space-y-3 text-white">
                        <h3 class="font-serif-italic italic text-2xl font-display">{{ __trans('Roasting Presisi', 'Precision Roasting') }}</h3>
                        <p class="font-sans font-light text-neutral-200 text-sm leading-relaxed max-h-0 opacity-0 group-hover:max-h-[100px] group-hover:opacity-100 overflow-hidden transition-all duration-500 ease-in-out">
                            {{ __trans('Setiap batch kopi dipanggang dalam kuantitas kecil dengan kontrol suhu mikro untuk menghasilkan cita rasa yang presisi dan konsisten.', 'Each batch of coffee is roasted in small quantities with micro temperature control to produce precise and consistent flavor.') }}
                        </p>
                        <p class="font-sans font-light text-neutral-400 text-xs tracking-wider group-hover:hidden transition-all duration-300">{{ __trans('Arahkan kursor untuk info detail →', 'Hover for details →') }}</p>
                    </div>
                </div>

                <!-- Feature 2: Sourcing -->
                <div class="group relative h-[380px] overflow-hidden flex flex-col justify-end p-8 border border-neutral-100 bg-[#121212]">
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-55" src="https://images.unsplash.com/photo-1524486361537-8ad15938e1a3?q=80&w=800&auto=format&fit=crop" alt="Regenerative Sourcing"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    </div>
                    <div class="relative z-10 space-y-3 text-white">
                        <h3 class="font-serif-italic italic text-2xl font-display">{{ __trans('Sourcing Regeneratif', 'Regenerative Sourcing') }}</h3>
                        <p class="font-sans font-light text-neutral-200 text-sm leading-relaxed max-h-0 opacity-0 group-hover:max-h-[100px] group-hover:opacity-100 overflow-hidden transition-all duration-500 ease-in-out">
                            {{ __trans('Bekerja sama langsung dengan petani lokal untuk menerapkan pertanian ramah lingkungan demi kelestarian tanah dan masa depan petani.', 'Partnering directly with local farmers to implement eco-friendly agriculture for soil preservation and the farmers\' future.') }}
                        </p>
                        <p class="font-sans font-light text-neutral-400 text-xs tracking-wider group-hover:hidden transition-all duration-300">{{ __trans('Arahkan kursor untuk info detail →', 'Hover for details →') }}</p>
                    </div>
                </div>

                <!-- Feature 3: Terroir -->
                <div class="group relative h-[380px] overflow-hidden flex flex-col justify-end p-8 border border-neutral-100 bg-[#121212]">
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-55" src="https://images.unsplash.com/photo-1501004318641-b39e6451bec6?q=80&w=800&auto=format&fit=crop" alt="Volcanic Terroir"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    </div>
                    <div class="relative z-10 space-y-3 text-white">
                        <h3 class="font-serif-italic italic text-2xl font-display">{{ __trans('Terroir Vulkanik', 'Volcanic Terroir') }}</h3>
                        <p class="font-sans font-light text-neutral-200 text-sm leading-relaxed max-h-0 opacity-0 group-hover:max-h-[100px] group-hover:opacity-100 overflow-hidden transition-all duration-500 ease-in-out">
                            {{ __trans('Biji kopi single-origin ditanam di ketinggian tinggi pada tanah vulkanik Andosol yang kaya mineral, menghasilkan cita rasa yang unik dan kaya rasa.', 'Single-origin coffee beans are grown at high altitudes in mineral-rich volcanic Andosol soil, producing unique and rich flavors.') }}
                        </p>
                        <p class="font-sans font-light text-neutral-400 text-xs tracking-wider group-hover:hidden transition-all duration-300">{{ __trans('Arahkan kursor untuk info detail →', 'Hover for details →') }}</p>
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
        // Inisialisasi galeri gambar dari backend
        const galleryImages = @json($galleryImages);
        let currentImageIndex = 0;

        // Thumbnail active image changer JS
        window.changeActiveImage = function(btn, imgUrl, index) {
            currentImageIndex = index;
            const mainImg = document.getElementById('main-product-image');
            if (mainImg) {
                mainImg.style.opacity = '0.3';
                setTimeout(() => {
                    mainImg.src = imgUrl;
                    mainImg.style.opacity = '1';
                }, 150);
            }

            // Update border style of thumbnails
            document.querySelectorAll('.gallery-thumb').forEach((thumb, idx) => {
                if (idx === index) {
                    thumb.classList.add('border-[#121212]', 'border-2');
                    thumb.classList.remove('border-neutral-200');
                } else {
                    thumb.classList.remove('border-[#121212]', 'border-2');
                    thumb.classList.add('border-neutral-200');
                }
            });
        };

        // Fungsi mengganti gambar ke sebelumnya
        window.prevImage = function() {
            if (galleryImages.length <= 1) return;
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            const targetUrl = galleryImages[currentImageIndex];
            const thumbs = document.querySelectorAll('.gallery-thumb');
            if (thumbs[currentImageIndex]) {
                window.changeActiveImage(thumbs[currentImageIndex], targetUrl, currentImageIndex);
            }
        };

        // Fungsi mengganti gambar ke berikutnya
        window.nextImage = function() {
            if (galleryImages.length <= 1) return;
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            const targetUrl = galleryImages[currentImageIndex];
            const thumbs = document.querySelectorAll('.gallery-thumb');
            if (thumbs[currentImageIndex]) {
                window.changeActiveImage(thumbs[currentImageIndex], targetUrl, currentImageIndex);
            }
        };

        // Horizontal Pill Selectors JS
        const sizePills = document.querySelectorAll('.size-pill');
        const nativeSelect = document.getElementById('grind');
        const priceDisplay = document.getElementById('product-price-display');

        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        sizePills.forEach(pill => {
            pill.addEventListener('click', () => {
                // Remove active classes
                sizePills.forEach(p => {
                    p.classList.remove('bg-[#121212]', 'text-white', 'border-[#121212]');
                    p.classList.add('bg-white', 'text-[#121212]', 'border-neutral-200');
                });
                
                // Add active classes to clicked
                pill.classList.add('bg-[#121212]', 'text-white', 'border-[#121212]');
                pill.classList.remove('bg-white', 'text-[#121212]', 'border-neutral-200');

                const value = pill.getAttribute('data-value');
                const price = parseInt(pill.getAttribute('data-price')) || {{ (int)$product->price }};
                
                if (nativeSelect) {
                    nativeSelect.value = value;
                }
                
                if (priceDisplay) {
                    priceDisplay.innerText = formatRupiah(price);
                }
            });
        });

        // Accordion JS Handler
        const triggers = document.querySelectorAll('.accordion-trigger');
        triggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                const content = trigger.nextElementSibling;
                const icon = trigger.querySelector('.material-symbols-outlined');
                
                // Close other accordions
                triggers.forEach(otherTrigger => {
                    if (otherTrigger !== trigger) {
                        const otherContent = otherTrigger.nextElementSibling;
                        const otherIcon = otherTrigger.querySelector('.material-symbols-outlined');
                        if (otherContent) otherContent.style.maxHeight = null;
                        if (otherIcon) otherIcon.innerText = 'add';
                    }
                });

                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                    if (icon) icon.innerText = 'add';
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                    if (icon) icon.innerText = 'remove';
                }
            });
        });

        const addToBagBtn = document.getElementById('add-to-bag-btn');
        if (addToBagBtn) {
            addToBagBtn.addEventListener('click', () => {
                const grindSize = nativeSelect ? nativeSelect.value : '100gr';
                
                // Ambil harga yang sesuai dengan ukuran yang dipilih
                const selectedPill = document.querySelector(`.size-pill[data-value="${grindSize}"]`);
                const currentPrice = selectedPill ? parseInt(selectedPill.getAttribute('data-price')) : {{ (int)$product->price }};

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
                        image: "{{ $product->image_url }}"
                    });
                }
                
                saveCart(cart);
                const addedMsg = AppLocale === 'en' ? `"${grindSize} - {{ $product->name }}" added.` : `"${grindSize} - {{ $product->name }}" ditambahkan.`;
                showToast(addedMsg);
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
