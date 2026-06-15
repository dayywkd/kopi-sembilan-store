@extends('layouts.app')

@section('title', 'The Roastery Lab | Toko Kopi Sembilan')

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
        filter: grayscale(100%) brightness(20%);
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
<!-- Lab Hero Section -->
<section class="snap-section bg-[#121212] pt-32 pb-20">
    <div class="bg-zoom-image" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBj6l-rRFFHMzvjVGWw_ggnajCN9iZfOSOH4bMhNK0PnTYkvujgACMXEHHwMSkbJOuUfkrQOwfHPmyiRf3-atkn0Y--pGjATz4V6-nVDR-NPFXXSeoolAsMVSFmAgmWeby5641gMgZz25zpA2VFFZZevqLirOs_fZG6-3NI0GKXSC-04MQxeBr5nHgL1OOqJhmkLGMnzzVUJiUVqkYCYdVK9_6D0y7TJYCknyE6ECS4vdWOt12U-OjXD4tHgv6uC-fkN6ktfcYdqDg'); opacity: 0.35;"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#121212_90%)] pointer-events-none z-[1]"></div>
    <div class="content-layer px-margin-mobile md:px-margin-desktop text-center py-20">
        <p class="label-tiny mb-8 opacity-60 reveal">Technical Center</p>
        <h1 class="text-display-hero font-display italic text-[#F9F9F9] reveal delay-100">The Roastery Lab.</h1>
        <p class="label-tiny mt-10 opacity-60 reveal delay-200">SCA Certified Profiles. Meticulous Thermodynamic Control.</p>
    </div>
</section>

<!-- Section 1: Roasting & Extraction (Split Grid) -->
<section class="bg-[#121212] py-stack-xl border-t border-[#F9F9F9]/5">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <!-- Item 1: Giesen -->
        <div class="col-span-12 lg:col-span-6 border-b lg:border-b-0 lg:border-r border-[#F9F9F9]/10 pb-12 lg:pb-0 lg:pr-12 flex flex-col justify-between reveal">
            <div>
                <p class="label-tiny mb-6 opacity-60">01 / Roasting</p>
                <h2 class="text-4xl md:text-5xl font-display text-[#F9F9F9] mb-8">Giesen W6A Roaster & Cropster</h2>
                <p class="font-sans font-light text-neutral-300 leading-relaxed text-base">
                    Our roasting facility is anchored by the Giesen W6A, integrated with advanced digital data logging. This system enables real-time monitoring of conductive-to-convective heat transfer, air temperature, and precise Rate-of-Rise (RoR) profiles, guaranteeing optimal consistency and clean flavor repeatability in every batch.
                </p>
            </div>
            <div class="mt-8">
                <span class="label-tiny text-xs bg-[#1a1a1a] border border-[#F9F9F9]/10 px-4 py-2 opacity-80">Thermodynamic profiling</span>
            </div>
        </div>

        <!-- Item 2: Modbar -->
        <div class="col-span-12 lg:col-span-6 pt-12 lg:pt-0 lg:pl-12 flex flex-col justify-between reveal delay-200">
            <div>
                <p class="label-tiny mb-6 opacity-60">02 / Extraction</p>
                <h2 class="text-4xl md:text-5xl font-display text-[#F9F9F9] mb-8">Modbar AV Volumetric System</h2>
                <p class="font-sans font-light text-neutral-300 leading-relaxed text-base">
                    The under-counter Modbar AV system removes visual barriers between barista and consumer, allowing absolute transparency. Pairing precise multi-boiler PID temperature control with automated volumetric extraction, it highlights the clean, delicate origin characteristics of our specialty micro-lots.
                </p>
            </div>
            <div class="mt-8">
                <span class="label-tiny text-xs bg-[#1a1a1a] border border-[#F9F9F9]/10 px-4 py-2 opacity-80">PID Volumetric Control</span>
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Grinding & Quality Control (Split Grid) -->
<section class="bg-[#181818] py-stack-xl border-y border-[#F9F9F9]/5">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <!-- Item 3: Mahlkonig -->
        <div class="col-span-12 lg:col-span-6 border-b lg:border-b-0 lg:border-r border-[#F9F9F9]/10 pb-12 lg:pb-0 lg:pr-12 flex flex-col justify-between reveal">
            <div>
                <p class="label-tiny mb-6 opacity-60">03 / Grinding</p>
                <h2 class="text-4xl md:text-5xl font-display text-[#F9F9F9] mb-8">Mahlkönig EK43</h2>
                <p class="font-sans font-light text-neutral-300 leading-relaxed text-base">
                    We grind our coffee using the industry-standard Mahlkönig EK43. Known for its unmatched particle distribution uniformity, this grinder minimizes fines and boulders, optimizing extraction yield and maximizing flavor clarity in the cup.
                </p>
            </div>
            <div class="mt-8">
                <span class="label-tiny text-xs bg-[#1a1a1a] border border-[#F9F9F9]/10 px-4 py-2 opacity-80">98mm Cast Steel Burrs</span>
            </div>
        </div>

        <!-- Item 4: Analysis -->
        <div class="col-span-12 lg:col-span-6 pt-12 lg:pt-0 lg:pl-12 flex flex-col justify-between reveal delay-200">
            <div>
                <p class="label-tiny mb-6 opacity-60">04 / Analysis</p>
                <h2 class="text-4xl md:text-5xl font-display text-[#F9F9F9] mb-8">VST Refractometer & Colorimetry</h2>
                <p class="font-sans font-light text-neutral-300 leading-relaxed text-base">
                    To maintain strict quality control, we analyze every roast batch. Total Dissolved Solids (TDS) are measured using VST refractometers to verify correct extraction yield percentages. We also analyze roast color on the Agtron scale to ensure precise conformity.
                </p>
            </div>
            <div class="mt-8">
                <span class="label-tiny text-xs bg-[#1a1a1a] border border-[#F9F9F9]/10 px-4 py-2 opacity-80">TDS & Agtron QC Control</span>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Water Chemistry -->
<section class="bg-[#121212] py-stack-xl">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto architectural-grid">
        <div class="col-span-12 lg:col-span-5 flex flex-col justify-center reveal">
            <p class="label-tiny mb-8 opacity-60">05 / Chemistry</p>
            <h2 class="text-5xl md:text-7xl font-display leading-tight mb-stack-md text-[#F9F9F9]">The Water Recipe.</h2>
            <p class="font-sans font-light text-lg md:text-xl leading-relaxed text-neutral-300 mt-6">
                Water accounts for roughly 98% of a brewed cup. Our laboratory utilizes a custom Reverse Osmosis system with custom mineral re-addition. Water is calibrated to exactly 150 TDS, with a precise calcium-to-magnesium ratio, ensuring balanced acidity and highlighting sweetness.
            </p>
        </div>
        <div class="col-span-12 lg:col-span-6 lg:col-start-7 flex flex-col justify-center reveal delay-200">
            <div class="mb-6 overflow-hidden aspect-[4/3] group border border-[#F9F9F9]/5">
                <img alt="Water remineralization system" class="w-full h-full object-cover grayscale contrast-125 transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6IIdl59MMJpuAvvPRdpWWiguAyNVLnXtzVptqMWVUfMWVpr8RGKDeNT01ETL3mmGIKOfoEB2-U6QfyTVRxNQODeB4ishs1U9ZzgYVCAqZbXqocFnIui3Hk5JVaSv9d2nCp5znNWfEFYxY2jDLp0P6_cCgq57_TQLYKMBd-l5-PDwamvtffIEqQ6qqjUJvW9svmuOZPSqZ2_LRHtQj7VVBdS2HksQQ7F4qqtxfEAKRVCRBzOKD_2SxFI_phTVVstSKexAySAErxWs"/>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-[#181818] py-stack-xl border-t border-[#F9F9F9]/5 text-center">
    <div class="content-layer px-margin-mobile md:px-margin-desktop max-w-2xl mx-auto reveal">
        <h2 class="text-4xl md:text-6xl font-display mb-8 text-[#F9F9F9] italic">Sip the Precision.</h2>
        <p class="font-sans font-light text-neutral-400 mb-12">
            Experience the result of our scientific roasting lab in your own cup.
        </p>
        <a href="{{ route('shop') }}" class="inline-block label-tiny btn-dark-outline px-12 py-6 font-bold tracking-wider">
            BROWSE OUR BEANS
        </a>
    </div>
</section>
@endsection
