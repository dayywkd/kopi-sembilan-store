<nav class="fixed top-0 left-0 w-full z-50 bg-transparent border-b border-transparent h-20 transition-all duration-500" 
     id="main-nav" 
     @if(Route::is('about')) data-theme-default="dark" @endif>
    <!-- Kontainer Navbar Menggunakan Flexbox Responsif untuk Alignment yang Sempurna -->
    <div class="flex lg:grid lg:grid-cols-3 justify-between items-center w-full max-w-[1440px] mx-auto px-6 md:px-16 h-full" id="main-nav-container">
        
        <!-- Kolom 1 (Kiri): Logo Brand Toko Kopi 9 -->
        <div class="flex items-center justify-start">
            <a href="{{ route('home') }}" class="flex items-center">
                <img id="nav-logo" alt="Toko Kopi Sembilan Logo" class="h-12 md:h-[52px] w-auto object-contain transition-all duration-300 hover:scale-101" src="{{ asset('images/logo_toko_kopi_9.png') }}"/>
            </a>
        </div>

        <!-- Kolom 2 (Tengah Presisi): Menu Navigasi Utama (SHOP, ABOUT, WHOLESALE) -->
        <div id="nav-menu-center" class="hidden lg:flex items-center justify-center gap-16 h-full text-[#111111] transition-colors duration-300" style="font-family: 'Inter', 'Hanken Grotesk', sans-serif;">
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('shop') ? 'border-current font-semibold' : 'border-transparent hover:border-current transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('shop') }}">{{ __trans('Belanja', 'Shop') }}</a>
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('about') ? 'border-current font-semibold' : 'border-transparent hover:border-current transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('about') }}">{{ __trans('Tentang', 'About') }}</a>
            <a class="text-[15px] font-medium tracking-[0.08em] uppercase pb-1 border-b-2 {{ Route::is('wholesale') ? 'border-current font-semibold' : 'border-transparent hover:border-current transition-all duration-200 ease-in-out opacity-90 hover:opacity-100' }}" href="{{ route('wholesale') }}">{{ __trans('Kemitraan', 'Wholesale') }}</a>
        </div>

        <!-- Kolom 3 (Kanan): Ikon Utility (Search, Account, Cart) -->
        <div id="nav-menu-right" class="flex items-center justify-end gap-6 text-[#111111] transition-colors duration-300">
            <!-- Language Switcher (Desktop) -->
            <div class="hidden md:flex items-center gap-1 text-[10px] font-bold tracking-[0.1em]" style="font-family: 'Inter', sans-serif;">
                <a href="{{ route('lang.switch', 'id') }}" class="{{ App::getLocale() === 'id' ? 'text-black underline underline-offset-4' : 'text-neutral-400 hover:text-black transition-colors' }}">ID</a>
                <span class="text-neutral-300">/</span>
                <a href="{{ route('lang.switch', 'en') }}" class="{{ App::getLocale() === 'en' ? 'text-black underline underline-offset-4' : 'text-neutral-400 hover:text-black transition-colors' }}">EN</a>
            </div>

            <!-- Ikon Pencarian -->
            <button onclick="openSearchOverlay()" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 cursor-pointer" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">
                search
            </button>

            <!-- Ikon Akun -->
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="/admin" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">person</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">person</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">person</a>
            @endauth

            <a id="cart-icon-btn" href="{{ route('cart.index') }}" class="material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 relative inline-block" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">
                shopping_bag
                <span id="cart-count" class="absolute -top-2.5 -right-2.5 bg-[#111111] text-white text-[10px] font-bold border border-[#FFFFFF] leading-none flex-shrink-0 aspect-square" style="box-sizing: border-box; width: 20px; height: 20px; min-width: 20px; min-height: 20px; border-radius: 50%; display: none; align-items: center; justify-content: center; padding: 0; line-height: 1;">0</span>
            </a>

            <!-- Hamburger Menu Mobile (Hanya tampil di layar < lg) -->
            <button onclick="toggleMobileMenu()" class="lg:hidden material-symbols-outlined text-[22px] font-light leading-none hover:opacity-70 transition-opacity duration-200 z-50" style="font-variation-settings: 'wght' 300, 'opsz' 24; font-size: 22px;">menu</button>
        </div>

    </div>
</nav>

<!-- Mobile Navigation Drawer & Background Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-[52] hidden opacity-0 transition-opacity duration-300" onclick="toggleMobileMenu()"></div>

<div id="mobile-menu" class="fixed top-0 right-0 h-full w-full sm:w-[380px] bg-[#FFFFFF] text-[#121212] z-[55] flex flex-col justify-between p-8 transition-transform duration-500 translate-x-full shadow-2xl border-l border-neutral-100">
    <div class="flex justify-between items-center py-6 border-b border-neutral-200">
        <span class="label-tiny opacity-60">Menu</span>
        <button onclick="toggleMobileMenu()" class="material-symbols-outlined text-2xl hover:text-brand-accent transition-colors text-[#121212]">close</button>
    </div>
    <div class="flex flex-col gap-6 text-2xl font-display italic my-auto text-[#121212] pl-2">
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors py-2 border-b border-neutral-50" href="{{ route('shop') }}">{{ __trans('Belanja', 'Shop') }}</a>
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors py-2 border-b border-neutral-50" href="{{ route('about') }}">{{ __trans('Tentang', 'About') }}</a>
        <a onclick="toggleMobileMenu()" class="hover:text-brand-accent transition-colors py-2" href="{{ route('wholesale') }}">{{ __trans('Kemitraan', 'Wholesale') }}</a>
    </div>
    <div class="pt-6 border-t border-neutral-200 flex justify-between items-center text-[#121212]">
        @auth
            @if (Auth::user()->role === 'admin')
                <a onclick="toggleMobileMenu()" href="/admin" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> {{ __trans('Panel Admin', 'Admin Panel') }}</a>
            @else
                <a onclick="toggleMobileMenu()" href="{{ route('customer.dashboard') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> {{ __trans('Akun', 'Account') }}</a>
            @endif
        @else
            <a onclick="toggleMobileMenu()" href="{{ route('login') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">person</span> {{ __trans('Masuk', 'Login') }}</a>
        @endauth
        <a onclick="toggleMobileMenu()" href="{{ route('cart.index') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity text-[#121212]"><span class="material-symbols-outlined text-base">shopping_bag</span> {{ __trans('Keranjang', 'Bag') }}</a>
    </div>
    <div class="pt-4 border-t border-neutral-100 flex justify-center gap-4 text-xs font-semibold uppercase tracking-widest text-[#121212]" style="font-family: 'Inter', sans-serif;">
        <a href="{{ route('lang.switch', 'id') }}" class="{{ App::getLocale() === 'id' ? 'text-black underline underline-offset-4' : 'text-neutral-400' }}">Bahasa</a>
        <span class="text-neutral-300">/</span>
        <a href="{{ route('lang.switch', 'en') }}" class="{{ App::getLocale() === 'en' ? 'text-black underline underline-offset-4' : 'text-neutral-400' }}">English</a>
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
