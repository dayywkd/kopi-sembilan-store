<nav class="fixed top-0 left-0 w-full z-50 bg-transparent border-b border-transparent h-20 transition-all duration-500" id="main-nav">
    <!-- Kontainer Navbar Menggunakan CSS Grid 3-Kolom untuk Alignment Tengah yang 100% Presisi & Simetris -->
    <div class="grid grid-cols-3 items-center w-full max-w-[1440px] mx-auto px-6 md:px-16 h-full" id="main-nav-container">
        
        <!-- Kolom 1 (Kiri): Logo Brand Toko Kopi 9 -->
        <div class="flex items-center justify-start">
            <a href="{{ route('home') }}" class="flex items-center">
                <img alt="Toko Kopi Sembilan Logo" class="h-12 md:h-[52px] w-auto object-contain transition-transform duration-200 hover:scale-101" src="{{ asset('images/logo_toko_kopi_9.png') }}"/>
            </a>
        </div>

        <!-- Kolom 2 (Tengah Presisi): Menu Navigasi Utama (SHOP, ABOUT, WHOLESALE) -->
        <div class="hidden lg:flex items-center justify-center gap-16 h-full text-[#111111]" style="font-family: 'Inter', 'Hanken Grotesk', sans-serif;">
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('shop') ? 'border-[#111111] font-semibold' : 'border-transparent hover:border-[#111111] transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('shop') }}">Shop</a>
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('about') ? 'border-[#111111] font-semibold' : 'border-transparent hover:border-[#111111] transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('about') }}">About</a>
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('wholesale') ? 'border-[#111111] font-semibold' : 'border-transparent hover:border-[#111111] transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('wholesale') }}">Wholesale</a>
        </div>

        <!-- Kolom 3 (Kanan): Ikon Utility (Search, Account, Cart) -->
        <div class="flex items-center justify-end gap-6 text-[#111111]">
            <!-- Ikon Pencarian -->
            <button onclick="openSearchOverlay()" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 cursor-pointer" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;" title="Cari Produk">
                search
            </button>

            <!-- Ikon Akun -->
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;" title="Dashboard Admin">person</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;" title="Dashboard Pelanggan">person</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;" title="Login / Daftar">person</a>
            @endauth

            <!-- Ikon Keranjang Belanja dengan Badge Hitam Minimalis -->
            <a href="{{ route('cart.index') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 relative" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;" title="Keranjang Belanja">
                shopping_bag
                <span id="cart-count" class="absolute -top-1.5 -right-1.5 bg-[#111111] text-white text-[9px] w-3.5 h-3.5 rounded-full flex items-center justify-center font-bold border border-white hidden">0</span>
            </a>

            <!-- Hamburger Menu Mobile (Hanya tampil di layar < lg) -->
            <button onclick="toggleMobileMenu()" class="lg:hidden material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 z-50" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">menu</button>
        </div>

    </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu" class="fixed inset-0 bg-[#FFFFFF] text-[#121212] z-[55] flex flex-col justify-between p-8 transition-transform duration-500 translate-x-full">
    <div class="flex justify-between items-center py-6 border-b border-neutral-200">
        <span class="label-tiny opacity-60">Menu</span>
        <button onclick="toggleMobileMenu()" class="material-symbols-outlined text-2xl hover:text-brand-accent transition-colors text-[#121212]">close</button>
    </div>
    <div class="flex flex-col gap-8 text-4xl font-display italic my-auto text-[#121212]">
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors" href="{{ route('shop') }}">Shop</a>
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors" href="{{ route('about') }}">About</a>
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors" href="{{ route('wholesale') }}">Wholesale</a>
    </div>
    <div class="pt-8 border-t border-neutral-200 flex justify-between items-center text-[#121212]">
        @auth
            @if (Auth::user()->role === 'admin')
                <a onclick="toggleMobileMenu()" href="{{ route('admin.dashboard') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> Admin Panel</a>
            @else
                <a onclick="toggleMobileMenu()" href="{{ route('customer.dashboard') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> Account</a>
            @endif
        @else
            <a onclick="toggleMobileMenu()" href="{{ route('login') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> Login</a>
        @endauth
        <a onclick="toggleMobileMenu()" href="{{ route('cart.index') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">shopping_bag</span> Bag</a>
    </div>
</div>

<!-- Search Overlay (Redesain ala Origin Coffee Style) -->
<div id="search-overlay" class="fixed inset-0 bg-[#FFFFFF]/98 backdrop-blur-md z-[100] flex flex-col justify-center px-6 md:px-20 transition-opacity duration-300 hidden opacity-0">
    <!-- Tombol Close di Pojok Kanan Atas -->
    <button onclick="closeSearchOverlay()" class="absolute top-8 right-8 md:right-20 material-symbols-outlined text-2xl text-[#111111] hover:opacity-60 transition-opacity cursor-pointer">
        close
    </button>
    
    <div class="max-w-5xl w-full mx-auto px-4">
        <!-- Layout Utama: Judul Kiri & Input Kapsul Kanan -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-12">
            <!-- Teks Judul Kiri (Menggunakan Font Sans-Serif Minimalis Premium) -->
            <div class="text-left">
                <h2 class="font-sans text-xl md:text-2xl font-semibold uppercase tracking-[0.15em] text-[#111111]">
                    Search Kopi Sembilan
                </h2>
            </div>
            
            <!-- Form Input Pencarian Kapsul Kanan (Hilangkan focus ring biru bawaan forms plugin) -->
            <form action="{{ route('shop') }}" method="GET" class="relative w-full md:max-w-[550px] flex-grow font-sans">
                <input type="text" name="search" id="search-overlay-input" placeholder="What is it you're looking for?" class="w-full py-3.5 pl-6 pr-12 text-sm border border-neutral-300 rounded-full outline-none text-[#111111] bg-[#FFFFFF] focus:ring-0 focus:border-[#111111] transition-all placeholder:text-neutral-400 font-medium">
                <button type="submit" class="absolute right-5 top-1/2 -translate-y-1/2 material-symbols-outlined text-xl text-[#111111] hover:opacity-60 transition-opacity cursor-pointer">
                    search
                </button>
            </form>
        </div>
    </div>
</div>
