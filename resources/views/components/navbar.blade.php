<nav class="fixed top-0 left-0 w-full z-50 bg-transparent border-b border-transparent transition-all duration-500" id="main-nav">
    <div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-8 max-w-container-max mx-auto transition-all duration-500" id="main-nav-container">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-4">
                <img alt="Toko Kopi Sembilan Logogram" class="h-8 w-auto mix-blend-difference" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9MpVXkkX9y3Q_Se_xUanS6w5p15bpo-B_mSEXVouJ0-V4stob2SLDcZ6bWOAJYDWK0fRxeiroHyw9gyc6lQ4wqWjNQVopzVTVzavgA9q4G_Jns1je7kyRMW9YZ8Pnm15y4fyR0LH8MTgR8r1_qhAsptFz0lEtXTkHQSYk8XazLz62-PN057iWfrwBk7eVgsTC1VvA0TDbXPbaKXpLPfLNNriXU2Oqpl9NhJc7ZOJNFjG0LIT3s4SDSER5NlbOjdVgl_TyBjuq3AU"/>
                <span class="label-tiny hidden sm:block mix-blend-difference font-semibold">Toko Kopi Sembilan</span>
            </a>
        </div>
        <div class="hidden lg:flex gap-12 items-center mix-blend-difference">
            <a class="label-tiny nav-link-hover {{ Route::is('home') ? 'opacity-100 border-b-2 border-[#F9F9F9] pb-1' : 'opacity-60 hover:opacity-100 transition-opacity pb-1' }}" href="{{ route('home') }}">Home</a>
            <a class="label-tiny nav-link-hover {{ Route::is('shop') ? 'opacity-100 border-b-2 border-[#F9F9F9] pb-1' : 'opacity-60 hover:opacity-100 transition-opacity pb-1' }}" href="{{ route('shop') }}">Shop</a>
            <a class="label-tiny nav-link-hover {{ Route::is('about') ? 'opacity-100 border-b-2 border-[#F9F9F9] pb-1' : 'opacity-60 hover:opacity-100 transition-opacity pb-1' }}" href="{{ route('about') }}">About</a>
            <a class="label-tiny nav-link-hover {{ Route::is('lab') ? 'opacity-100 border-b-2 border-[#F9F9F9] pb-1' : 'opacity-60 hover:opacity-100 transition-opacity pb-1' }}" href="{{ route('lab') }}">Lab</a>
            <a class="label-tiny nav-link-hover opacity-60 hover:opacity-100 transition-opacity pb-1" href="{{ Route::is('home') ? '#visit' : route('home') . '#visit' }}">Visit</a>
        </div>
        <div class="flex gap-8 items-center mix-blend-difference relative">
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="material-symbols-outlined text-xl hover:opacity-60 transition-opacity" title="Dashboard Admin">person</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="material-symbols-outlined text-xl hover:opacity-60 transition-opacity" title="Dashboard Pelanggan">person</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="material-symbols-outlined text-xl hover:opacity-60 transition-opacity" title="Login / Daftar">person</a>
            @endauth
            <a href="{{ route('cart.index') }}" class="material-symbols-outlined text-xl hover:opacity-60 transition-opacity relative" title="Keranjang Belanja">
                shopping_bag
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">0</span>
            </a>
            <button onclick="toggleMobileMenu()" class="lg:hidden material-symbols-outlined text-xl hover:opacity-60 transition-opacity z-50">menu</button>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Drawer -->
<div id="mobile-menu" class="fixed inset-0 bg-[#121212] z-[55] flex flex-col justify-between p-8 transition-transform duration-500 translate-x-full">
    <div class="flex justify-between items-center py-6 border-b border-[#F9F9F9]/5">
        <span class="label-tiny opacity-60">Menu</span>
        <button onclick="toggleMobileMenu()" class="material-symbols-outlined text-xl hover:opacity-60 transition-opacity">close</button>
    </div>
    <div class="flex flex-col gap-8 text-4xl font-display italic my-auto">
        <a onclick="toggleMobileMenu()" class="hover:text-neutral-400 transition-colors" href="{{ route('shop') }}">Shop</a>
        <a onclick="toggleMobileMenu()" class="hover:text-neutral-400 transition-colors" href="{{ route('about') }}">About</a>
        <a onclick="toggleMobileMenu()" class="hover:text-neutral-400 transition-colors" href="{{ route('lab') }}">Lab</a>
        <a onclick="toggleMobileMenu()" class="hover:text-neutral-400 transition-colors" href="{{ route('home') }}#visit">Visit</a>
    </div>
    <div class="pt-8 border-t border-[#F9F9F9]/5 flex justify-between items-center">
        @auth
            @if (Auth::user()->role === 'admin')
                <a onclick="toggleMobileMenu()" href="{{ route('admin.dashboard') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"><span class="material-symbols-outlined text-base">person</span> Admin Panel</a>
            @else
                <a onclick="toggleMobileMenu()" href="{{ route('customer.dashboard') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"><span class="material-symbols-outlined text-base">person</span> Account</a>
            @endif
        @else
            <a onclick="toggleMobileMenu()" href="{{ route('login') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"><span class="material-symbols-outlined text-base">person</span> Login</a>
        @endauth
        <a onclick="toggleMobileMenu()" href="{{ route('cart.index') }}" class="label-tiny flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity"><span class="material-symbols-outlined text-base">shopping_bag</span> Bag</a>
    </div>
</div>
