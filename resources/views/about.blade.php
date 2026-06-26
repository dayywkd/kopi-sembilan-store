@extends('layouts.app')

@section('title', 'About Us | Toko Kopi Sembilan')
@section('meta_description', 'Ketahui lebih dalam mengenai sejarah perjalanan roastery Toko Kopi Sembilan, komitmen kualitas specialty coffee kami, dan dedikasi kami pada petani lokal.')

@section('styles')
<style>
    .text-display-hero {
        font-size: clamp(2.5rem, 8vw, 6rem);
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
    .btn-dark {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #121212;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-weight: 700;
    }
    .btn-dark:hover {
        background-color: transparent;
        color: #121212;
        transform: translateY(-2px);
    }
    
    .gallery-img {
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- Hero Section (Revealed - Video Loop Background) -->
<section class="relative min-h-[65vh] flex items-center justify-center bg-black pt-24 border-b border-neutral-800 reveal overflow-hidden">
    <!-- Video Loop Background (Aesthetic Slow Motion Coffee Roasting) -->
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-45 z-0">
        <source src="{{ asset('videos/roasting_loop.mp4') }}" type="video/mp4">
    </video>
    <!-- Dark Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-black/80 z-10"></div>
    
    <div class="relative px-margin-mobile md:px-margin-desktop max-w-4xl mx-auto text-center py-16 z-20">
        <p class="label-tiny mb-4 text-neutral-400">Our Heritage & Story</p>
        <h1 class="text-display-hero font-display italic text-white mb-6">Murni & Presisi.<br/>Perjalanan Kami.</h1>
        <p class="font-sans font-light text-sm md:text-base text-neutral-300 max-w-xl mx-auto leading-relaxed">
            Est. MMXXIV in Tuban — Dedikasi penuh untuk membakar biji kopi pilihan dengan standar ketat SCA guna menyajikan profil rasa terbaik bagi para pecinta kopi di Indonesia.
        </p>
    </div>
</section>

<!-- Our Story Section (Revealed) -->
<section class="bg-white py-24 border-b border-neutral-100 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">01 / The Origin</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">Bagaimana Semuanya Dimulai</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    Toko Kopi Sembilan didirikan pada tahun 2024 di kawasan bersejarah Kutorejo, Tuban. Berawal dari komunitas kecil penyuka kopi spesialis, roastery kami berfokus untuk mengeksplorasi cita rasa asli dari biji kopi micro-lot Indonesia dan dunia. Kami percaya bahwa setiap biji kopi memiliki cerita unik yang dapat diungkapkan melalui sangraian yang presisi.
                </p>
            </div>
            <!-- Menggunakan Foto Area Bar Kopi & Roaster Asli User -->
            <div class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-neutral-100 border border-neutral-200 shadow-sm">
                <img alt="heritage_roastery_bar" class="w-full h-full object-cover" src="{{ asset('images/about_2.jpg') }}"/>
            </div>
        </div>
    </div>
</section>

<!-- The Values Section (Revealed) -->
<!-- The Values Section (Revealed - Roasted Beans Background & Glassmorphism) -->
<section class="relative py-24 border-b border-neutral-900 reveal overflow-hidden" style="background-image: url('{{ asset('images/roasting_bg.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Dark/Warm Overlay -->
    <div class="absolute inset-0 bg-[#121212]/85 z-0"></div>
    
    <div class="relative px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto z-10">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <p class="label-tiny text-neutral-400">02 / Core Pillars</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white italic">Pilar Toko Kopi Sembilan</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">Sourcing Terpilih</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    Kami bermitra secara bertanggung jawab dengan petani lokal untuk mendapatkan ceri kopi pilihan yang matang sempurna. Setiap lot biji kopi melewati proses kurasi ketat.
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">Standar SCA Certified</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    Setiap profil sangrai dianalisis dan di-evaluasi sesuai standar Specialty Coffee Association (SCA) untuk menjamin kualitas rasa serta konsistensi tingkat kematangan biji kopi.
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">Kemurnian Rasa</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    Tujuan utama kami adalah menyajikan karakter rasa asli (*terroir*) dari setiap daerah asal kopi tanpa manipulasi tambahan, menghasilkan seduhan yang bersih dan kaya.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Roastery Gallery Section (Revealed) -->
<section class="bg-white py-24 border-b border-neutral-100 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto space-y-12">
        <div class="text-center space-y-3">
            <p class="label-tiny text-neutral-400">03 / Gallery</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic">Dokumentasi Roastery Kami</h2>
            <p class="font-sans text-xs text-neutral-400 max-w-sm mx-auto leading-relaxed">
                Potret sudut ruangan pemanggangan, bar penyajian, sertifikat kualitas, dan area bersantai outdoor kami di Tuban.
            </p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Foto 1: Sertifikat Rak Kopi --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_1.jpg') }}" alt="Sertifikat SCA Certified" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 2: Bar & Roaster --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_2.jpg') }}" alt="Mesin Roaster & Bar" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 3: Wide View Bar --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_3.jpg') }}" alt="Sudut Luas Bar Kopi Sembilan" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 4: Luar Kedai Halaman Kerikil --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_4.jpg') }}" alt="Tampak Depan Kedai" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 5: Tempat Duduk Outdoor --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[16/9] lg:aspect-[3/4] col-span-2 lg:col-span-1 group bg-neutral-50">
                <img src="{{ asset('images/about_5.jpg') }}" alt="Area Bersantai Outdoor" class="gallery-img w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Location & Contact Info Section (Revealed) -->
<section class="bg-white py-24 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">04 / Information</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">Kontak & Lokasi Kami</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    Hubungi tim roastery kami untuk pertanyaan seputar ketersediaan produk, kritik & saran, atau sekadar berdiskusi mengenai seduhan kopi favorit Anda.
                </p>
                
                <div class="space-y-4 pt-6 border-t border-neutral-200 text-xs">
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">Telepon</span>
                        <a href="tel:+6285336688839" class="font-medium text-neutral-700 hover:text-black transition-colors">+62 853-3668-8839</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">Email</span>
                        <a href="mailto:owner@kopisembilan.com" class="font-medium text-neutral-700 hover:text-black transition-colors">owner@kopisembilan.com</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">Instagram</span>
                        <a href="https://www.instagram.com/tokokopisembilan" target="_blank" class="font-medium text-neutral-700 hover:text-black transition-colors">@tokokopisembilan</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">Alamat</span>
                        <span class="text-neutral-700 text-right font-medium leading-relaxed">
                            Jl. Pemuda Kutorejo Gg. II No.230,<br>Kutorejo, Kec. Tuban, Kabupaten Tuban,<br>Jawa Timur 62311
                        </span>
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

<!-- Call to Action (Video Loop Background - Revealed) -->
<section class="relative py-28 text-center reveal overflow-hidden bg-black">
    <!-- Video Loop Background (Aesthetic Slow Motion Coffee Brewing) -->
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-35 z-0">
        <source src="{{ asset('videos/brewing_loop.mp4') }}" type="video/mp4">
    </video>
    <!-- Dark Gradient Overlay -->
    <div class="absolute inset-0 bg-black/60 z-10"></div>
    
    <div class="relative max-w-2xl mx-auto px-margin-mobile space-y-6 z-20">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-white italic">Nikmati Kopi Terbaik Hari Ini</h2>
        <p class="text-xs text-neutral-300 leading-relaxed font-light">
            Temukan koleksi biji kopi single origin dan blend unggulan kami yang dipanggang dengan penuh kehati-hatian.
        </p>
        <a href="{{ route('shop') }}" class="inline-block bg-white text-black hover:bg-neutral-200 transition-all px-12 py-5 label-tiny tracking-wider font-bold">
            MULAI BELANJA
        </a>
    </div>
</section>
@endsection
