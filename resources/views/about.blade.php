@extends('layouts.app')

@section('title', 'About Us | Toko Kopi Sembilan')

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
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center justify-center bg-neutral-50 pt-24 border-b border-neutral-200">
    <div class="px-margin-mobile md:px-margin-desktop max-w-4xl mx-auto text-center py-16">
        <p class="label-tiny mb-4 text-neutral-400">Our Heritage & Story</p>
        <h1 class="text-display-hero font-display italic text-[#121212] mb-6">Murni & Presisi.<br/>Perjalanan Kami.</h1>
        <p class="font-sans font-light text-sm md:text-base text-neutral-500 max-w-xl mx-auto leading-relaxed">
            Est. MMXXIV in Tuban — Dedikasi penuh untuk membakar biji kopi pilihan dengan standar ketat SCA guna menyajikan profil rasa terbaik bagi para pecinta kopi di Indonesia.
        </p>
    </div>
</section>

<!-- Our Story Section -->
<section class="bg-white py-24 border-b border-neutral-100">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">01 / The Origin</p>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic leading-tight">Bagaimana Semuanya Dimulai</h2>
                <p class="text-sm text-neutral-500 leading-relaxed font-light">
                    Toko Kopi Sembilan didirikan pada tahun 2024 di kawasan bersejarah Kutorejo, Tuban. Berawal dari komunitas kecil penyuka kopi spesialis, roastery kami berfokus untuk mengeksplorasi cita rasa asli dari biji kopi micro-lot Indonesia dan dunia. Kami percaya bahwa setiap biji kopi memiliki cerita unik yang dapat diungkapkan melalui sangraian yang presisi.
                </p>
            </div>
            <div class="lg:col-span-7 aspect-[16/10] overflow-hidden bg-neutral-100 border border-neutral-200">
                <img alt="Heritage image" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6IIdl59MMJpuAvvPRdpWWiguAyNVLnXtzVptqMWVUfMWVpr8RGKDeNT01ETL3mmGIKOfoEB2-U6QfyTVRxNQODeB4ishs1U9ZzgYVCAqZbXqocFnIui3Hk5JVaSv9d2nCp5znNWfEFYxY2jDLp0P6_cCgq57_TQLYKMBd-l5-PDwamvtffIEqQ6qqjUJvW9svmuOZPSqZ2_LRHtQj7VVBdS2HksQQ7F4qqtxfEAKRVCRBzOKD_2SxFI_phTVVstSKexAySAErxWs"/>
            </div>
        </div>
    </div>
</section>

<!-- The Values Section -->
<section class="bg-neutral-50 py-24 border-b border-neutral-100">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <p class="label-tiny text-neutral-400">02 / Core Pillars</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic">Pilar Toko Kopi Sembilan</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 border border-neutral-200 space-y-4">
                <h3 class="text-lg font-bold text-[#121212]">Sourcing Terpilih</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">
                    Kami bermitra secara bertanggung jawab dengan petani lokal untuk mendapatkan ceri kopi pilihan yang matang sempurna. Setiap lot biji kopi melewati proses kurasi ketat.
                </p>
            </div>
            <div class="bg-white p-8 border border-neutral-200 space-y-4">
                <h3 class="text-lg font-bold text-[#121212]">Standar SCA Certified</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">
                    Setiap profil sangrai dianalisis dan dievaluasi sesuai standar Specialty Coffee Association (SCA) untuk menjamin kualitas rasa serta konsistensi tingkat kematangan biji kopi.
                </p>
            </div>
            <div class="bg-white p-8 border border-neutral-200 space-y-4">
                <h3 class="text-lg font-bold text-[#121212]">Kemurnian Rasa</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">
                    Tujuan utama kami adalah menyajikan karakter rasa asli (*terroir*) dari setiap daerah asal kopi tanpa manipulasi tambahan, menghasilkan seduhan yang bersih dan kaya.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Location & Contact Info Section -->
<section class="bg-white py-24">
    <div class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-5 space-y-6">
                <p class="label-tiny text-neutral-400">03 / Information</p>
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

<!-- FAQ Section -->
<section id="faq" class="bg-white py-24 border-t border-neutral-100 scroll-mt-24">
    <div class="px-margin-mobile md:px-margin-desktop max-w-4xl mx-auto">
        <div class="text-center mb-16 space-y-4">
            <p class="label-tiny text-neutral-400">04 / FAQ</p>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic">Pertanyaan Umum (FAQ)</h2>
        </div>
        
        <div class="space-y-6">
            <div class="border-b border-neutral-200 pb-6">
                <h3 class="text-base font-bold text-[#121212] mb-3">Bagaimana cara menyimpan biji kopi agar tetap segar?</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">Simpan biji kopi di dalam wadah kedap udara yang buram di tempat yang sejuk dan gelap. Hindari menyimpan kopi di dalam kulkas atau freezer karena dapat merusak rasa dan aroma kopi akibat kondensasi.</p>
            </div>
            <div class="border-b border-neutral-200 pb-6">
                <h3 class="text-base font-bold text-[#121212] mb-3">Apakah kopi yang dijual sudah dalam bentuk bubuk?</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">Secara default kami menyajikan dalam bentuk biji utuh (whole beans) agar kesegarannya terjaga maksimal. Namun, Anda dapat memilih ukuran gilingan (kasar, sedang, atau halus) di halaman detail produk sebelum menambahkannya ke keranjang.</p>
            </div>
            <div class="border-b border-neutral-200 pb-6">
                <h3 class="text-base font-bold text-[#121212] mb-3">Berapa lama proses pengiriman pesanan?</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">Setiap pesanan yang masuk akan diproses dan dikemas dalam waktu 1-2 hari kerja. Untuk pengiriman, waktu sampainya tergantung pada kurir ekspedisi pilihan Anda (seperti JNE, J&T, atau SiCepat) dan lokasi pengiriman Anda.</p>
            </div>
            <div class="border-b border-neutral-200 pb-6">
                <h3 class="text-base font-bold text-[#121212] mb-3">Apakah Toko Kopi Sembilan menerima kemitraan B2B?</h3>
                <p class="text-xs text-neutral-500 leading-relaxed font-light">Tentu! Kami menyediakan program wholesale (grosir) untuk coffee shop, restoran, kantor, maupun hotel dengan harga khusus B2B. Silakan isi formulir pengajuan kerja sama di menu utama **Wholesale**.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-neutral-50 py-20 text-center border-t border-neutral-200">
    <div class="max-w-2xl mx-auto px-margin-mobile space-y-6">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-[#121212] italic">Nikmati Kopi Terbaik Hari Ini</h2>
        <p class="text-xs text-neutral-500 leading-relaxed font-light">
            Temukan koleksi biji kopi single origin dan blend unggulan kami yang dipanggang dengan penuh kehati-hatian.
        </p>
        <a href="{{ route('shop') }}" class="inline-block btn-dark px-12 py-5 label-tiny tracking-wider">
            MULAI BELANJA
        </a>
    </div>
</section>
@endsection
