@extends('layouts.app')

@section('title', 'About Us | Toko Kopi Sembilan')

@section('styles')
<style>
    .text-display-hero {
        font-size: clamp(3rem, 10vw, 8rem);
        line-height: 0.95;
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
    .bg-zoom-image {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        filter: grayscale(100%) brightness(25%);
        z-index: 0;
    }
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
    .snap-section {
        min-height: 70vh;
        width: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<!-- About Hero Section -->
<section class="snap-section bg-[#121212] pt-32 pb-20">
    <div class="bg-zoom-image" style="background-image: url('https://lh3.googleusercontent.com/aida/AP1WRLv7olZyxkGvr_JPGc8W0FNVQEVf-TEoc86wJFAOz88IOS2fU__1PHRqrm5U03va8UfQaRW2a77G73_GWauW9cARdwQsvqUdcAysQLxu9GIVL-lMB-TH3-iI960PwfAhQPxEs9LGK9FJYAX45HHONwilt5CQKJMntQe65Zzmu_TsLxyE-uoTO3XjRtHBnb_NxYPq0WVGThRxVYm9To_H2uE0624KoFz5QuSkZkdOOHQPjSEtJ3ywsivduXc'); opacity: 0.35;"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#121212_90%)] pointer-events-none z-[1]"></div>
    <div class="content-layer px-margin-mobile md:px-margin-desktop text-center py-20">
        <p class="label-tiny mb-8 opacity-60 reveal">Our Heritage</p>
        <h1 class="text-display-hero font-display italic text-[#F9F9F9] reveal delay-100">Pure Precision.<br/>Our Journey.</h1>
        <p class="label-tiny mt-10 opacity-60 reveal delay-200">Est. MMXXIV in Tuban — A Sanctuary for Specialty Coffee</p>
    </div>
</section>

<!-- Section 1: The Genesis -->
<section id="genesis" class="bg-[#121212] py-stack-xl overflow-hidden border-t border-[#F9F9F9]/5">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <div class="col-span-12 lg:col-span-5 flex flex-col justify-center reveal">
            <p class="label-tiny mb-8 opacity-60">01 / The Origin</p>
            <h2 class="text-5xl md:text-7xl font-display leading-tight mb-stack-md text-[#F9F9F9]">How It<br/>Started.</h2>
            <p class="font-sans font-light text-lg md:text-xl leading-relaxed text-neutral-300 mt-6">
                Toko Kopi Sembilan was founded in 2024 in the historical heart of Kutorejo, Tuban. Established by a collective of coffee purists, the roastery began as a humble, non-commercial showroom. It was created as a sanctuary where friends and local connoisseurs could gather, taste, and discuss the nuances of premium beans.
            </p>
        </div>
        <div class="col-span-12 lg:col-span-6 lg:col-start-7 flex flex-col justify-center reveal delay-200">
            <div class="mb-6 overflow-hidden aspect-[4/3] group border border-[#F9F9F9]/5">
                <img alt="Coffee Roastery Heritage" class="w-full h-full object-cover grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6IIdl59MMJpuAvvPRdpWWiguAyNVLnXtzVptqMWVUfMWVpr8RGKDeNT01ETL3mmGIKOfoEB2-U6QfyTVRxNQODeB4ishs1U9ZzgYVCAqZbXqocFnIui3Hk5JVaSv9d2nCp5znNWfEFYxY2jDLp0P6_cCgq57_TQLYKMBd-l5-PDwamvtffIEqQ6qqjUJvW9svmuOZPSqZ2_LRHtQj7VVBdS2HksQQ7F4qqtxfEAKRVCRBzOKD_2SxFI_phTVVstSKexAySAErxWs"/>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: The Science -->
<section id="science" class="bg-[#181818] py-stack-xl border-y border-[#F9F9F9]/5 text-[#F9F9F9]">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <div class="col-span-12 lg:col-span-6 flex flex-col justify-center reveal order-2 lg:order-1">
            <div class="mb-6 overflow-hidden aspect-[4/3] group border border-[#F9F9F9]/5">
                <img alt="Precision Roasting Equipment" class="w-full h-full object-cover grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBj6l-rRFFHMzvjVGWw_ggnajCN9iZfOSOH4bMhNK0PnTYkvujgACMXEHHwMSkbJOuUfkrQOwfHPmyiRf3-atkn0Y--pGjATz4V6-nVDR-NPFXXSeoolAsMVSFmAgmWeby5641gMgZz25zpA2VFFZZevqLirOs_fZG6-3NI0GKXSC-04MQxeBr5nHgL1OOqJhmkLGMnzzVUJiUVqkYCYdVK9_6D0y7TJYCknyE6ECS4vdWOt12U-OjXD4tHgv6uC-fkN6ktfcYdqDg"/>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-5 lg:col-start-8 flex flex-col justify-center reveal delay-200 order-1 lg:order-2 mb-12 lg:mb-0">
            <p class="label-tiny mb-8 opacity-60">02 / Technical Superiority</p>
            <h2 class="text-5xl md:text-7xl font-display leading-tight mb-stack-md text-[#F9F9F9]">SCA Certified<br/>Standards.</h2>
            <p class="font-sans font-light text-lg md:text-xl leading-relaxed text-neutral-300 mt-6">
                In 2025, we elevated our commitment by certifying our profiles with Specialty Coffee Association (SCA) standards. We established the Roastery Lab, implementing strict thermodynamic monitoring and cupping protocols. Every single batch is tracked scientificially to guarantee consistent extraction and pure flavor repeatability.
            </p>
        </div>
    </div>
</section>

<!-- Section 3: Core Pillars -->
<section id="pillars" class="bg-[#121212] py-stack-xl">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="text-center mb-16 reveal">
            <p class="label-tiny mb-4 opacity-60">Our Values</p>
            <h2 class="text-5xl md:text-7xl font-display text-[#F9F9F9]">The Pillars of Sembilan.</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-stretch">
            <div class="border border-[#F9F9F9]/5 p-8 bg-[#1a1a1a]/40 flex flex-col justify-between reveal delay-100">
                <div>
                    <h3 class="text-2xl font-display text-[#F9F9F9] mb-6">Meticulous Sourcing</h3>
                    <p class="font-sans font-light text-neutral-300 leading-relaxed text-sm">
                        We partner directly with farmers to source select micro-lots. Each bean is handpicked and graded to meet our strict specialty requirements.
                    </p>
                </div>
            </div>
            <div class="border border-[#F9F9F9]/5 p-8 bg-[#1a1a1a]/40 flex flex-col justify-between reveal delay-200">
                <div>
                    <h3 class="text-2xl font-display text-[#F9F9F9] mb-6">Thermodynamic Control</h3>
                    <p class="font-sans font-light text-neutral-300 leading-relaxed text-sm">
                        Using advanced roasting analytics, we control heat and air transfer precisely to roast every origin according to its optimum sensory profile.
                    </p>
                </div>
            </div>
            <div class="border border-[#F9F9F9]/5 p-8 bg-[#1a1a1a]/40 flex flex-col justify-between reveal delay-300">
                <div>
                    <h3 class="text-2xl font-display text-[#F9F9F9] mb-6">Sensory Expression</h3>
                    <p class="font-sans font-light text-neutral-300 leading-relaxed text-sm">
                        Our ultimate goal is purity. We profile coffee to highlight the unadulterated terroir of its origin, delivering a clean and expressive extraction.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-[#181818] py-stack-xl border-t border-[#F9F9F9]/5 text-center">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-2xl mx-auto reveal">
        <h2 class="text-4xl md:text-6xl font-display mb-8 text-[#F9F9F9] italic">Taste the Precision.</h2>
        <p class="font-sans font-light text-neutral-400 mb-12">
            Explore our curated inventory of specialty beans, meticulously roasted for coffee enthusiasts.
        </p>
        <a href="{{ route('shop') }}" class="inline-block label-tiny btn-dark-outline px-12 py-6 font-bold tracking-wider">
            EXPLORE THE COLLECTION
        </a>
    </div>
</section>
@endsection
