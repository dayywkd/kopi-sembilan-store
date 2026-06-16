@extends('layouts.app')

@section('title', 'Toko Kopi Sembilan | Specialty Coffee | SCA Certified | Roastery')

@section('styles')
<style>
    .text-display-hero {
        font-size: clamp(3.5rem, 12vw, 10rem);
        line-height: 0.9;
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
    @keyframes slow-zoom {
        0% { transform: scale(1.02); }
        100% { transform: scale(1.10); }
    }
    .bg-zoom-image {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: grayscale(100%) brightness(30%);
        z-index: 0;
        animation: slow-zoom 25s infinite alternate ease-in-out;
    }
    @keyframes fade-up {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-up {
        opacity: 0;
        animation: fade-up 1.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .hero-delay-1 { animation-delay: 200ms; }
    .hero-delay-2 { animation-delay: 400ms; }
    .hero-delay-3 { animation-delay: 600ms; }
    
    .content-layer {
        position: relative;
        z-index: 10;
        width: 100%;
    }
    .architectural-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 24px;
    }
    
    /* Button States */
    .btn-inversion {
        background-color: #F9F9F9;
        color: #121212;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 700;
    }
    .btn-inversion:hover {
        background-color: transparent;
        color: #F9F9F9;
        box-shadow: inset 0 0 0 1px #F9F9F9;
        transform: translateY(-2px);
    }
    .btn-dark-outline {
        background-color: transparent;
        color: #F9F9F9;
        border: 1px solid #444444;
        transition: all 0.3s ease;
    }
    .btn-dark-outline:hover {
        background-color: #F9F9F9;
        color: #121212;
        border-color: #F9F9F9;
        transform: translateY(-2px);
    }
    
    .nav-link-hover {
        transition: color 0.3s ease, opacity 0.3s ease;
    }
    .nav-link-hover:hover {
        color: #F9F9F9;
        opacity: 1;
    }
    .product-image-container {
        position: relative;
        overflow: hidden;
    }
    .product-image-container img {
        transition: filter 0.4s ease, transform 0.6s ease;
    }
    .product-image-container:hover img {
        filter: grayscale(0%) contrast(110%);
        transform: scale(1.05);
    }
    .product-overlay {
        position: absolute;
        inset: 0;
        background: rgba(18,18,18,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-image-container:hover .product-overlay {
        opacity: 1;
    }
    .snap-section {
        min-height: 90vh;
        width: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="snap-section bg-[#121212]">
    <div class="bg-zoom-image" style="background-image: url('https://lh3.googleusercontent.com/aida/AP1WRLv7olZyxkGvr_JPGc8W0FNVQEVf-TEoc86wJFAOz88IOS2fU__1PHRqrm5U03va8UfQaRW2a77G73_GWauW9cARdwQsvqUdcAysQLxu9GIVL-lMB-TH3-iI960PwfAhQPxEs9LGK9FJYAX45HHONwilt5CQKJMntQe65Zzmu_TsLxyE-uoTO3XjRtHBnb_NxYPq0WVGThRxVYm9To_H2uE0624KoFz5QuSkZkdOOHQPjSEtJ3ywsivduXc'); opacity: 0.5;"></div>
    <div class="content-layer px-margin-mobile md:px-margin-desktop text-center py-32">
        <p class="label-tiny mb-12 opacity-60 animate-fade-up hero-delay-1">Tuban, Indonesia</p>
        <h1 class="text-display-hero font-display italic text-[#F9F9F9] animate-fade-up hero-delay-2">Precision Roasting.<br/>Pure Expression.</h1>
        <p class="label-tiny mt-12 opacity-60 animate-fade-up hero-delay-3">Est. MMXXIV — Specialty Coffee Roastery & SCA Certified Profiles</p>
    </div>
</section>

<!-- Story Section -->
<section id="story" class="snap-section bg-[#121212] overflow-hidden py-stack-xl">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <div class="col-span-12 lg:col-span-5 flex flex-col justify-center reveal">
            <p class="label-tiny mb-8 opacity-60">01 / The Origin</p>
            <h2 class="text-5xl md:text-7xl font-display leading-tight mb-stack-md text-[#F9F9F9]">SCA Certified<br/>Specialty Coffee Roastery.</h2>
        </div>
        <div class="col-span-12 lg:col-span-6 lg:col-start-7 flex flex-col justify-center space-y-12 reveal delay-200">
            <div class="mb-12 overflow-hidden aspect-[4/3] group">
                <img alt="Barista pouring latte art" class="w-full h-full object-cover grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6IIdl59MMJpuAvvPRdpWWiguAyNVLnXtzVptqMWVUfMWVpr8RGKDeNT01ETL3mmGIKOfoEB2-U6QfyTVRxNQODeB4ishs1U9ZzgYVCAqZbXqocFnIui3Hk5JVaSv9d2nCp5znNWfEFYxY2jDLp0P6_cCgq57_TQLYKMBd-l5-PDwamvtffIEqQ6qqjUJvW9svmuOZPSqZ2_LRHtQj7VVBdS2HksQQ7F4qqtxfEAKRVCRBzOKD_2SxFI_phTVVstSKexAySAErxWs"/>
            </div>
            <p class="font-sans font-light text-xl md:text-2xl leading-relaxed text-neutral-300">
                Toko Kopi Sembilan arrives in Tuban as a sanctuary for specialty coffee roasting. We dedicate ourselves to the highest standards of architectural craftsmanship, meticulously sourcing select green beans and sculpting their sensory profiles with uncompromised precision for coffee connoisseurs across Indonesia.
            </p>
            <div class="pt-4">
                <a href="{{ route('about') }}" class="label-tiny btn-dark-outline px-10 py-6 w-fit font-bold uppercase tracking-wider text-center inline-block">
                    OUR STORY &amp; HERITAGE
                </a>
            </div>
        </div>
    </div>
    <img alt="watermark" class="absolute -bottom-20 -right-20 w-1/3 opacity-[0.03] pointer-events-none" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9MpVXkkX9y3Q_Se_xUanS6w5p15bpo-B_mSEXVouJ0-V4stob2SLDcZ6bWOAJYDWK0fRxeiroHyw9gyc6lQ4wqWjNQVopzVTVzavgA9q4G_Jns1je7kyRMW9YZ8Pnm15y4fyR0LH8MTgR8r1_qhAsptFz0lEtXTkHQSYk8XazLz62-PN057iWfrwBk7eVgsTC1VvA0TDbXPbaKXpLPfLNNriXU2Oqpl9NhJc7ZOJNFjG0LIT3s4SDSER5NlbOjdVgl_TyBjuq3AU"/>
</section>

<!-- The Lab Section -->
<section id="lab" class="snap-section bg-[#181818] border-y border-[#F9F9F9]/5 text-[#F9F9F9] py-stack-xl">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid w-full h-full">
        <div class="col-span-12 lg:col-span-7 border-r border-[#F9F9F9]/10 md:py-margin-desktop pr-0 lg:pr-12 flex flex-col justify-center reveal">
            <p class="label-tiny mb-12 opacity-60">02 / Technical Superiority</p>
            <h2 class="text-6xl md:text-8xl font-display mb-12 text-[#F9F9F9]">The Roastery Lab.</h2>
            <p class="font-sans font-light text-2xl md:text-3xl max-w-xl leading-snug text-neutral-300">
                Every roasting profile is meticulously engineered using a scientific approach and rigorous thermodynamic control systems to guarantee optimal flavor consistency and repeatability in every single batch.
            </p>
        </div>
        <div class="col-span-12 lg:col-span-5 md:py-margin-desktop pl-0 lg:pl-12 flex flex-col justify-end reveal delay-200">
            <div class="mb-12 overflow-hidden aspect-[4/3] group">
                <img alt="Precision espresso equipment" class="w-full h-full object-cover grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBj6l-rRFFHMzvjVGWw_ggnajCN9iZfOSOH4bMhNK0PnTYkvujgACMXEHHwMSkbJOuUfkrQOwfHPmyiRf3-atkn0Y--pGjATz4V6-nVDR-NPFXXSeoolAsMVSFmAgmWeby5641gMgZz25zpA2VFFZZevqLirOs_fZG6-3NI0GKXSC-04MQxeBr5nHgL1OOqJhmkLGMnzzVUJiUVqkYCYdVK9_6D0y7TJYCknyE6ECS4vdWOt12U-OjXD4tHgv6uC-fkN6ktfcYdqDg"/>
            </div>
            <div class="space-y-12">
                <div class="h-px w-full bg-[#F9F9F9]/10 opacity-60"></div>
                <p class="font-sans font-light text-sm leading-relaxed opacity-80 text-neutral-300">
                    From estate-level green bean selection to rigorous post-roast cupping protocols, we ensure our premium single-origin offerings are perfectly tailored to meet and exceed your highest extraction expectations.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('lab') }}" class="label-tiny btn-dark-outline px-8 py-5 w-fit font-bold uppercase tracking-wider text-center inline-block">
                        EXPLORE THE LAB
                    </a>
                    <a href="{{ route('shop') }}" class="label-tiny btn-inversion px-8 py-5 w-fit font-bold uppercase tracking-wider text-center inline-block">
                        EXPLORE COLLECTION
                    </a>
                </div>
            </div>
        </div>
    </div>
    <img alt="watermark" class="absolute -top-40 -left-40 w-1/2 opacity-[0.02] pointer-events-none" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCt0ggYtu_Zco4jGCO3BnrAtZUSuiWNw-clZniMjWOPrRTjWAGqd_vtq29QwwlWK92aWQZcGhql7sEyQirmsBRWoYWHeN-qpDZTp_jD9oT7WoSvMhMwn5aH0ZVY8CJKqo6dkHNaFmZnfxgJu53-QsJpQg_DAkDxpyUqP5yIfayFSesbfum4D3Fv3sRrS_lEXr2-U4EokVPy62-X2jDTDHx8aCs_e96B1BrfznBzc6YjgbedpIkeLfANJenVt-Twa6yxH-muiQ1gObw"/>
</section>

<!-- Featured Beans Section -->
<section class="bg-[#121212] py-stack-xl border-b border-[#F9F9F9]/5">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8 reveal">
            <div>
                <p class="label-tiny mb-4 opacity-60">Curated Inventory</p>
                <h2 class="text-5xl md:text-7xl font-display text-[#F9F9F9]">Our Beans.</h2>
            </div>
            <a class="label-tiny border-b border-[#F9F9F9]/20 pb-2 nav-link-hover opacity-60" href="{{ route('shop') }}">View Full Collection</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-stretch">
            @forelse ($featuredProducts as $index => $product)
                <!-- Dynamic Product -->
                <div class="group flex flex-col h-full border border-[#F9F9F9]/5 p-6 hover:border-[#F9F9F9]/20 transition-all bg-[#1a1a1a]/40 reveal {{ 'delay-' . (($index + 1) * 100) }}">
                    <div class="aspect-square bg-[#1a1a1a] product-image-container mb-6">
                        <img alt="{{ $product->name }}" class="w-full h-full object-cover grayscale" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w"/>
                        <div class="product-overlay">
                            <span class="label-tiny text-white font-bold tracking-widest">{{ $product->category->name }}</span>
                        </div>
                    </div>
                    <div class="space-y-4 flex-grow flex flex-col justify-between">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex flex-col gap-1.5">
                                <h3 class="text-xl font-display text-[#F9F9F9] hover:text-[#c6c6c6] transition-colors">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
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
                            <p class="label-tiny text-[#F9F9F9] font-bold text-lg whitespace-nowrap">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-sm text-neutral-300 font-sans leading-relaxed">{{ $product->sensory_notes }}</p>
                        <button onclick="addToBag({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ (is_array($product->sizes) && count($product->sizes) > 0) ? $product->sizes[0]['size'] : '100gr' }}')" class="w-full py-5 btn-inversion label-tiny tracking-wider">ADD TO BAG</button>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="label-tiny opacity-60">No products available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Visit Section -->
<section id="visit" class="snap-section bg-[#121212]">
    <div class="bg-zoom-image" style="background-image: url('https://lh3.googleusercontent.com/aida/AP1WRLv7olZyxkGvr_JPGc8W0FNVQEVf-TEoc86wJFAOz88IOS2fU__1PHRqrm5U03va8UfQaRW2a77G73_GWauW9cARdwQsvqUdcAysQLxu9GIVL-lMB-TH3-iI960PwfAhQPxEs9LGK9FJYAX45HHONwilt5CQKJMntQe65Zzmu_TsLxyE-uoTO3XjRtHBnb_NxYPq0WVGThRxVYm9To_H2uE0624KoFz5QuSkZkdOOHQPjSEtJ3ywsivduXc'); filter: grayscale(100%) brightness(20%);"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#121212_90%)] pointer-events-none z-[1]"></div>
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid z-10">
        <div class="col-span-12 mb-10">
            <h2 class="text-display-hero font-display italic text-[#F9F9F9] tracking-tight">Showroom</h2>
        </div>
        <div class="col-span-12 lg:col-span-4 lg:col-start-9 bg-black/60 backdrop-blur-xl border border-[#F9F9F9]/15 text-[#F9F9F9] p-6 md:p-8 flex flex-col justify-between transition-all duration-500 hover:border-[#F9F9F9]/30 hover:shadow-[0_20px_50px_rgba(0,0,0,0.5)] transform hover:-translate-y-1 reveal">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <p class="label-tiny opacity-60">Tuban Roastery Showroom</p>
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                </div>
                <!-- Embedded Dark Mode Map -->
                <div class="w-full h-28 border border-[#F9F9F9]/10 grayscale invert brightness-[0.8] contrast-[1.2] mb-4 overflow-hidden">
                    <iframe 
                        src="https://maps.google.com/maps?q=Toko%20Kopi%20Sembilan%20Kutorejo%20Tuban&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        class="w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <div class="space-y-4 pt-4 border-t border-[#F9F9F9]/10">
                <div class="flex justify-between items-baseline gap-4">
                    <span class="label-tiny opacity-60 text-xs shrink-0">Location</span>
                    <span class="font-sans font-light text-xs md:text-sm text-right text-neutral-200 leading-normal">
                        Jl. Pemuda Kutorejo Gg. II No.230,<br/>Kutorejo, Tuban, East Java, ID
                    </span>
                </div>
                <div class="pt-2">
                    <a href="https://www.google.com/maps/search/?api=1&query=Toko+Kopi+Sembilan+Kutorejo+Tuban" target="_blank" class="w-full inline-flex justify-center items-center gap-2 py-3 border border-[#F9F9F9]/20 hover:border-[#F9F9F9] hover:bg-[#F9F9F9] hover:text-[#121212] transition-all duration-300 label-tiny font-bold tracking-wider text-[11px]">
                        GET DIRECTIONS 
                        <span class="material-symbols-outlined text-xs">open_in_new</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

