@extends('layouts.app')

@section('title', __trans('Tentang Kami | Toko Kopi Sembilan', 'About Us | Toko Kopi Sembilan'))
@section('meta_description', __trans('Ketahui lebih dalam mengenai sejarah perjalanan roastery Toko Kopi Sembilan, komitmen kualitas specialty coffee kami, dan dedikasi kami pada petani lokal.', 'Learn more about the history of Toko Kopi Sembilan roastery, our specialty coffee quality commitment, and our dedication to local farmers.'))

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
        <p class="label-tiny mb-4 text-neutral-400">{{ __trans('Warisan & Cerita Kami', 'Our Heritage & Story') }}</p>
        <h1 class="text-display-hero font-display italic text-white mb-6">{!! __trans('Murni & Presisi.<br/>Perjalanan Kami.', 'Pure & Precise.<br/>Our Journey.') !!}</h1>
        <p class="font-sans font-light text-sm md:text-base text-neutral-300 max-w-xl mx-auto leading-relaxed">
            {{ __trans('Est. MMXXIV di Tuban — Dedikasi penuh untuk roasting biji kopi pilihan dengan standar ketat SCA guna menyajikan profil rasa terbaik bagi para pecinta kopi di Indonesia.', 'Est. MMXXIV in Tuban — Dedicated to roasting selected coffee beans under strict SCA standards to deliver the best flavor profile for coffee lovers in Indonesia.') }}
        </p>
    </div>
</section>

<!-- Our Story Section (Revealed) -->
<section class="bg-white py-24 border-b border-neutral-100 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">{{ __trans('01 / Asal Mula', '01 / The Origin') }}</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">{{ __trans('Bagaimana Semuanya Dimulai', 'How It All Began') }}</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    {{ __trans('Toko Kopi Sembilan didirikan pada tahun 2024 di kawasan bersejarah Kutorejo, Tuban. Berawal dari komunitas kecil penyuka kopi spesialis, roastery kami berfokus untuk mengeksplorasi cita rasa asli dari biji kopi micro-lot Indonesia dan dunia. Kami percaya bahwa setiap biji kopi memiliki cerita unik yang dapat diungkapkan melalui roasting yang presisi.', 'Toko Kopi Sembilan was founded in 2024 in the historic area of Kutorejo, Tuban. Starting as a small community of specialty coffee enthusiasts, our roastery focuses on exploring the authentic flavors of micro-lot coffee beans from Indonesia and around the world. We believe that every coffee bean has a unique story that can be revealed through precision roasting.') }}
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
<section class="relative py-24 border-b border-neutral-900 reveal overflow-hidden" style="background-image: url('{{ asset('images/roasting_bg.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <!-- Dark/Warm Overlay -->
    <div class="absolute inset-0 bg-[#121212]/85 z-0"></div>
    
    <div class="relative px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto z-10">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <p class="label-tiny text-neutral-400">{{ __trans('02 / Pilar Utama', '02 / Core Pillars') }}</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white italic">{{ __trans('Pilar Toko Kopi Sembilan', 'Pillars of Sembilan Coffee') }}</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">{{ __trans('Sourcing Terpilih', 'Selected Sourcing') }}</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    {{ __trans('Kami bermitra secara bertanggung jawab dengan petani lokal untuk mendapatkan ceri kopi pilihan yang matang sempurna. Setiap lot biji kopi melewati proses kurasi ketat.', 'We partner responsibly with local farmers to source perfectly ripe coffee cherries. Every lot of coffee beans goes through a strict curation process.') }}
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">{{ __trans('Standar SCA Certified', 'SCA Certified Standards') }}</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    {{ __trans('Setiap profil roasting dianalisis dan di-evaluasi sesuai standar Specialty Coffee Association (SCA) untuk menjamin kualitas rasa serta konsistensi tingkat kematangan biji kopi.', 'Every roasting profile is analyzed and evaluated according to Specialty Coffee Association (SCA) standards to guarantee flavor quality and consistency in coffee bean development.') }}
                </p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 border border-white/10 space-y-4 hover:scale-[1.03] transition-transform duration-500 shadow-xl group">
                <h3 class="text-lg font-bold text-white group-hover:text-neutral-300 transition-colors">{{ __trans('Kemurnian Rasa', 'Purity of Flavor') }}</h3>
                <p class="text-xs text-neutral-300 leading-relaxed font-light">
                    {{ __trans('Tujuan utama kami adalah menyajikan karakter rasa asli (terroir) dari setiap daerah asal kopi tanpa manipulasi tambahan, menghasilkan seduhan yang bersih dan kaya.', 'Our main goal is to present the authentic flavor character (terroir) of each coffee origin without any additional manipulation, producing clean and rich brews.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Roastery Gallery Section (Revealed) -->
<section class="bg-white py-24 border-b border-neutral-100 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto space-y-12">
        <div class="text-center space-y-3">
            <p class="label-tiny text-neutral-400">{{ __trans('03 / Galeri', '03 / Gallery') }}</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic">{{ __trans('Dokumentasi Roastery Kami', 'Our Roastery Gallery') }}</h2>
            <p class="font-sans text-xs text-neutral-400 max-w-sm mx-auto leading-relaxed">
                {{ __trans('Potret sudut ruangan pemanggangan, bar penyajian, sertifikat kualitas, dan area bersantai outdoor kami di Tuban.', 'Snapshots of our roasting room, serving bar, quality certificates, and outdoor relaxation area in Tuban.') }}
            </p>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Foto 1: Sertifikat Rak Kopi --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_1.jpg') }}" alt="{{ __trans('Sertifikat SCA Certified', 'SCA Certified Certificate') }}" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 2: Bar & Roaster --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_2.jpg') }}" alt="{{ __trans('Mesin Roaster & Bar', 'Roaster Machine & Bar') }}" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 3: Wide View Bar --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_3.jpg') }}" alt="{{ __trans('Sudut Luas Bar Kopi Sembilan', 'Wide View of Sembilan Coffee Bar') }}" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 4: Luar Kedai Halaman Kerikil --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[3/4] group bg-neutral-50">
                <img src="{{ asset('images/about_4.jpg') }}" alt="{{ __trans('Tampak Depan Kedai', 'Store Front View') }}" class="gallery-img w-full h-full object-cover">
            </div>
            {{-- Foto 5: Tempat Duduk Outdoor --}}
            <div class="gallery-card overflow-hidden border border-neutral-200 aspect-[16/9] lg:aspect-[3/4] col-span-2 lg:col-span-1 group bg-neutral-50">
                <img src="{{ asset('images/about_5.jpg') }}" alt="{{ __trans('Area Bersantai Outdoor', 'Outdoor Seating Area') }}" class="gallery-img w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>

<!-- Location & Contact Info Section (Revealed) -->
<section class="bg-white py-24 reveal">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">{{ __trans('04 / Informasi', '04 / Information') }}</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">{{ __trans('Kontak & Lokasi Kami', 'Our Contact & Location') }}</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    {{ __trans('Hubungi tim roastery kami untuk pertanyaan seputar ketersediaan produk, kritik & saran, atau sekadar berdiskusi mengenai seduhan kopi favorit Anda.', 'Contact our roastery team for questions regarding product availability, feedback, or simply to discuss your favorite coffee brews.') }}
                </p>
                
                <div class="space-y-4 pt-6 border-t border-neutral-200 text-xs">
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">{{ __trans('Telepon', 'Phone') }}</span>
                        <a href="tel:+6285336688839" class="font-medium text-neutral-700 hover:text-black transition-colors">+62 853-3668-8839</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">{{ __trans('Email', 'Email') }}</span>
                        <a href="mailto:owner@kopisembilan.com" class="font-medium text-neutral-700 hover:text-black transition-colors">owner@kopisembilan.com</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">{{ __trans('Instagram', 'Instagram') }}</span>
                        <a href="https://www.instagram.com/tokokopisembilan" target="_blank" class="font-medium text-neutral-700 hover:text-black transition-colors">@tokokopisembilan</a>
                    </div>
                    <div class="flex justify-between items-baseline py-1">
                        <span class="label-tiny text-neutral-400">{{ __trans('Alamat', 'Address') }}</span>
                        <span class="text-neutral-700 text-right font-medium leading-relaxed">
                            Jl. Pemuda Kutorejo Gg. II No.230,<br>Kutorejo, Kec. Tuban, Kabupaten Tuban,<br>{!! __trans('Jawa Timur', 'East Java') !!} 62311
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
        <h2 class="text-3xl md:text-4xl font-display font-bold text-white italic">{{ __trans('Nikmati Kopi Terbaik Hari Ini', 'Enjoy the Finest Coffee Today') }}</h2>
        <p class="text-xs text-neutral-300 leading-relaxed font-light">
            {{ __trans('Temukan koleksi biji kopi single origin dan blend unggulan kami yang dipanggang dengan penuh kehati-hatian.', 'Discover our collection of outstanding single origin and blend coffee beans roasted with utmost care.') }}
        </p>
        <a href="{{ route('shop') }}" class="inline-block bg-white text-black hover:bg-neutral-200 transition-all px-12 py-5 label-tiny tracking-wider font-bold">
            {{ __trans('MULAI BELANJA', 'SHOP NOW') }}
        </a>
    </div>
</section>
@endsection
