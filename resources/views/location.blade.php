@extends('layouts.app')

@section('title', 'Lokasi Roastery Kami | Toko Kopi Sembilan')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }
    
    .gallery-img {
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    
    /* Gmaps container shadow */
    .gmaps-container {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
        border: 1px solid #E5E7EB;
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen pb-24 bg-white">
    
    {{-- Header --}}
    <section class="px-margin-mobile md:px-margin-desktop py-16 text-center max-w-container-max mx-auto border-b border-[#E5E7EB] mb-16 reveal">
        <span class="label-tiny text-[#5B5B5B] block mb-3">KUNJUNGI KAMI</span>
        <h1 class="font-display text-4xl md:text-6xl uppercase italic text-brand-dark leading-tight">Lokasi Roastery</h1>
        <p class="font-sans text-sm text-neutral-500 max-w-md mx-auto mt-4 leading-relaxed">
            Menyajikan kopi segar beraroma khas yang dipanggang langsung di Kutorejo, Tuban. Datang dan rasakan aromanya.
        </p>
    </section>

    {{-- Detail Lokasi & Google Maps --}}
    <section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-24 reveal">
        
        <!-- Info Text (Left) -->
        <div class="lg:col-span-5 space-y-8">
            <div class="space-y-4">
                <h3 class="label-tiny text-neutral-400">Alamat Lengkap</h3>
                <p class="font-display text-2xl md:text-3xl italic text-[#121212] leading-tight font-bold">
                    Toko Kopi Sembilan
                </p>
                <p class="font-sans text-sm text-neutral-600 leading-relaxed">
                    Jl. Pemuda Kutorejo Gg. II No.230, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311
                </p>
            </div>
            
            <div class="space-y-4">
                <h3 class="label-tiny text-neutral-400">Kontak WA</h3>
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[#000000]">call</span>
                    <a href="https://wa.me/6285336688839" target="_blank" class="font-sans text-base font-bold text-neutral-800 hover:text-[#5B5B5B] transition-colors">
                        085336688839
                    </a>
                </div>
            </div>

            <div class="space-y-4 pt-6 border-t border-[#E5E7EB]">
                <h3 class="label-tiny text-neutral-400">Jam Operasional</h3>
                <div class="font-sans text-sm text-neutral-600 space-y-2 leading-relaxed">
                    <p class="flex justify-between border-b border-[#F9F9F9] pb-1">
                        <span>Selasa - Kamis:</span>
                        <span class="font-semibold text-neutral-800">10.00 - 15.00, 17.00 - 23.00</span>
                    </p>
                    <p class="flex justify-between border-b border-[#F9F9F9] pb-1">
                        <span>Jumat:</span>
                        <span class="font-semibold text-neutral-800">14.00 - 23.00</span>
                    </p>
                    <p class="flex justify-between border-b border-[#F9F9F9] pb-1">
                        <span>Sabtu - Minggu:</span>
                        <span class="font-semibold text-neutral-800">07.00 - 15.00, 17.00 - 23.00</span>
                    </p>
                    <p class="flex justify-between text-red-600">
                        <span>Senin:</span>
                        <span class="font-semibold">Tutup</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Google Maps (Right) -->
        <div class="lg:col-span-7 gmaps-container w-full h-[400px] lg:h-[480px] overflow-hidden bg-neutral-100">
            <iframe 
                src="https://maps.google.com/maps?q=Toko%20Kopi%20Sembilan%20Kutorejo%20Tuban&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
    </section>

    {{-- Galeri Foto Roastery --}}
    <section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto border-t border-[#E5E7EB] pt-20 reveal">
        <div class="text-center mb-16 space-y-3">
            <span class="label-tiny text-neutral-400">GALERI FOTO</span>
            <h2 class="font-display text-3xl md:text-5xl uppercase italic text-brand-dark font-bold">Suasana Roastery</h2>
            <p class="font-sans text-xs text-neutral-400 italic">
                *Foto-foto berikut adalah dokumentasi suasana pemanggangan dan penyajian kami di Tuban.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Foto 1 --}}
            <div class="gallery-card overflow-hidden bg-neutral-100 border border-[#E5E7EB]">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/roastery_1.png') }}" 
                         alt="Proses Roasting Biji Kopi Sembilan" 
                         class="gallery-img w-full h-full object-cover">
                </div>
                <div class="p-5 bg-white text-center border-t border-[#E5E7EB]">
                    <span class="label-tiny text-[9px] text-neutral-400">PROSES ROASTING</span>
                    <p class="font-display text-sm font-semibold italic text-[#121212] mt-1">Mesin Roasting Kopi</p>
                </div>
            </div>
            
            {{-- Foto 2 --}}
            <div class="gallery-card overflow-hidden bg-neutral-100 border border-[#E5E7EB]">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/roastery_2.png') }}" 
                         alt="Interior Toko Kopi Sembilan" 
                         class="gallery-img w-full h-full object-cover">
                </div>
                <div class="p-5 bg-white text-center border-t border-[#E5E7EB]">
                    <span class="label-tiny text-[9px] text-neutral-400">INTERIOR KEDAI</span>
                    <p class="font-display text-sm font-semibold italic text-[#121212] mt-1">Suasana Bar Kopi</p>
                </div>
            </div>
            
            {{-- Foto 3 --}}
            <div class="gallery-card overflow-hidden bg-neutral-100 border border-[#E5E7EB]">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ asset('images/roastery_3.png') }}" 
                         alt="Penyeduhan Manual Kopi V60" 
                         class="gallery-img w-full h-full object-cover">
                </div>
                <div class="p-5 bg-white text-center border-t border-[#E5E7EB]">
                    <span class="label-tiny text-[9px] text-neutral-400">PENYEDUHAN MANUAL</span>
                    <p class="font-display text-sm font-semibold italic text-[#121212] mt-1">Sajian V60 Fresh Brew</p>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
