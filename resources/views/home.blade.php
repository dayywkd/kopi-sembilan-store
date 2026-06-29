@extends('layouts.app')

@section('title', __trans('Toko Kopi Sembilan | Specialty Coffee | SCA Certified | Roastery', 'Sembilan Coffee Shop | Specialty Coffee | SCA Certified | Roastery'))
@section('meta_description', __trans('Toko Kopi Sembilan adalah roastery kopi lokal di Tuban yang menyediakan biji kopi pilihan (specialty coffee) berkualitas tinggi dengan standar SCA Certified Roastery.', 'Toko Kopi Sembilan is a local specialty coffee roastery in Tuban offering premium coffee beans with SCA Certified standards.'))

@section('styles')
<style>
    .text-display-hero {
        font-size: clamp(2.5rem, 8vw, 6.5rem);
        line-height: 1.0;
        font-weight: 700;
        letter-spacing: -0.04em;
    }
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
    
    /* Button States - Clean Light & Dark style */
    .btn-dark {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #121212;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-weight: 700;
    }
    .btn-dark:hover {
        background-color: transparent;
        border-color: #121212;
        color: #121212;
        transform: translateY(-2px);
    }
    
    .btn-outline-dark {
        background-color: transparent;
        color: #121212;
        border: 1px solid #121212;
        transition: all 0.3s ease;
    }
    .btn-outline-dark:hover {
        background-color: #121212;
        border-color: #121212;
        color: #ffffff;
        transform: translateY(-2px);
    }
    
    .product-image-container {
        position: relative;
        overflow: hidden;
        background-color: #F9F9F9;
        border: 1px solid #e5e7eb;
    }
    .product-image-container img {
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .product-image-container:hover img {
        transform: scale(1.03);
    }
</style>
@endsection

@section('content')
<!-- Minimalist Hero Section (2-Column Premium Layout) -->
<section class="min-h-[85vh] bg-white pt-20 border-b border-neutral-100 flex flex-col md:grid md:grid-cols-2 reveal">
    <!-- Kolom Kiri: Teks & CTA -->
    <div class="flex flex-col justify-center items-start px-6 md:pl-20 md:pr-16 py-16 md:py-24 bg-white">
        <h1 class="font-sans font-bold text-4xl md:text-5xl lg:text-[54px] text-[#121212] leading-[1.1] tracking-tight mb-6">
            {!! __trans('Edisi Terbatas:<br/>Rilisan Khas Sembilan', 'Limited Edition:<br/>Sembilan Signature Release') !!}
        </h1>
        <p class="font-sans text-neutral-600 text-sm md:text-base leading-relaxed mb-8 max-w-md">
            {{ __trans('Menjelajahi rangkaian varietas kopi kami yang paling istimewa melalui metode penyangraian presisi oleh Toko Kopi Sembilan.', 'Exploring the range of our most celebrated coffee varieties through precision roasting methods by Toko Kopi Sembilan.') }}
        </p>
        
        <a href="{{ route('shop') }}" class="w-full md:w-auto bg-[#121212] text-white hover:bg-neutral-800 transition-colors text-center py-4 px-16 rounded-full text-xs font-bold tracking-widest uppercase mb-8 block">
            {{ __trans('Beli sekarang', 'Shop now') }}
        </a>
        
        <div class="font-sans text-[11px] text-neutral-500 space-y-1">
            <p class="tracking-wider uppercase">Toko Kopi Sembilan — Specialty Coffee Roasters</p>
            <p class="flex items-center gap-1">
                <span>{{ __trans('Lebih dari 10.000', 'Over 10,000') }}</span>
                <span class="flex text-[#121212]">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1, 'wght' 400; font-size: 14px;">star</span>
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1, 'wght' 400; font-size: 14px;">star</span>
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1, 'wght' 400; font-size: 14px;">star</span>
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1, 'wght' 400; font-size: 14px;">star</span>
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1, 'wght' 400; font-size: 14px;">star</span>
                </span>
                <span>{{ __trans('ulasan', 'reviews') }}</span>
            </p>
        </div>
    </div>
    
    <!-- Kolom Kanan: Asset Gambar Kopi Premium -->
    <div class="w-full min-h-[400px] md:min-h-0 h-full bg-neutral-50 overflow-hidden relative">
        <img src="{{ asset('images/hero_gesha_release.png') }}" alt="Limited Edition: Double Gesha Release" class="w-full h-full object-cover object-center absolute inset-0 transition-transform duration-700 hover:scale-102">
    </div>
</section>

<!-- Featured Best Seller Beans Section -->
<section class="bg-white py-24 border-b border-neutral-100 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div>
                <p class="label-tiny mb-3 text-neutral-400">{{ __trans('Pilihan Terkurasi', 'Curated Favorites') }}</p>
                <h2 class="text-4xl md:text-5xl font-display text-[#121212] italic font-bold">{{ __trans('Biji Kopi Terlaris.', 'Best Seller Beans.') }}</h2>
            </div>
            <a class="label-tiny border-b border-[#121212]/30 pb-2 text-[#121212] font-semibold hover:border-brand-accent hover:text-brand-accent transition-colors" href="{{ route('shop') }}">
                {{ __trans('Lihat Semua Koleksi', 'View All Collection') }} &rarr;
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse ($featuredProducts as $product)
                <!-- Product Card -->
                <div class="group flex flex-col h-full bg-white transition-all">
                    <a href="{{ route('product.show', $product->slug) }}" class="product-image-container block aspect-square mb-6">
                        @if($product->image_path)
                            <img alt="{{ $product->name }}" class="w-full h-full object-cover" src="{{ $product->image_url }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-300 text-xs tracking-widest font-mono">NO IMAGE</div>
                        @endif
                    </a>
                    
                    <div class="space-y-3 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-4">
                                <h3 class="text-lg font-bold text-[#121212] hover:text-brand-accent transition-colors leading-tight">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-sm font-semibold text-[#121212] whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[11px] text-neutral-400 uppercase tracking-widest mt-1">{{ $product->category->name }}</p>
                            
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
                        
                        <div class="pt-4 space-y-3">
                            <p class="text-xs text-neutral-500 line-clamp-2 leading-relaxed min-h-[32px]">{{ $product->sensory_notes }}</p>
                            @if ($product->stock <= 0 || $product->status === 'SOLD OUT')
                                <button disabled class="w-full py-3.5 bg-neutral-200 border border-neutral-300 text-neutral-400 label-tiny tracking-wider text-[11px] cursor-not-allowed">
                                    {{ __trans('HABIS', 'SOLD OUT') }}
                                </button>
                            @else
                                <button onclick="addToBag({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ (is_array($product->sizes) && count($product->sizes) > 0) ? $product->sizes[0]['size'] : '100gr' }}', '{{ $product->image_url }}')" class="w-full py-3.5 btn-dark label-tiny tracking-wider text-[11px]">
                                    {{ __trans('TAMBAH KE KERANJANG', 'ADD TO BAG') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="label-tiny text-neutral-400">{{ __trans('Belum ada produk unggulan yang ditandai sebagai Best Seller.', 'No featured products marked as Best Seller yet.') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Minimalist Showroom Section -->
<section id="visit" class="bg-brand-cream py-24 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">{{ __trans('Showroom Tuban', 'Tuban Showroom') }}</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">{{ __trans('Datang & Nikmati Kopi di Roastery Kami', 'Visit & Enjoy Coffee at Our Roastery') }}</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    {{ __trans('Nikmati suasana tenang roastery kami di pusat kota Tuban. Anda dapat mencicipi langsung sajian kopi single origin musiman kami, berkonsultasi mengenai profil sangrai dengan barista kami, atau membeli biji kopi segar langsung dari rak penyimpanan.', 'Enjoy the serene atmosphere of our roastery in the heart of Tuban. You can taste our seasonal single origin coffee offerings, consult roasting profiles with our baristas, or purchase fresh coffee beans directly from our shelves.') }}
                </p>
                <div class="space-y-3 pt-4 border-t border-[#E5E7EB]">
                    <div class="flex justify-between items-baseline gap-4 text-xs">
                        <span class="label-tiny text-neutral-400">{{ __trans('Alamat', 'Address') }}</span>
                        <span class="text-neutral-600 text-right leading-relaxed font-medium">
                            Jl. Pemuda Kutorejo Gg. II No.230,<br/>Kutorejo, Tuban, Jawa Timur 62311
                        </span>
                    </div>
                    <div class="pt-2">
                        <a href="https://www.google.com/maps/search/?api=1&query=Toko+Kopi+Sembilan+Kutorejo+Tuban" target="_blank" class="w-full inline-flex justify-center items-center gap-2 py-4 border border-neutral-300 hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all duration-300 label-tiny font-bold text-[11px]">
                            {{ __trans('PETUNJUK ARAH', 'GET DIRECTIONS') }}
                            <span class="material-symbols-outlined text-xs">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-7 h-[350px] border border-neutral-200 overflow-hidden shadow-sm">
                <iframe 
                    src="https://maps.google.com/maps?q=Toko%20Kopi%20Sembilan%20Kutorejo%20Tuban&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                    class="w-full h-full border-0" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection
