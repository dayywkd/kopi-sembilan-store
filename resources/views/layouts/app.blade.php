<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Toko Kopi Sembilan | Specialty Coffee | SCA Certified | Roastery')</title>
    
    <!-- Tailwind CSS & Plugins (CDN) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100;300;400;700;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#1a1a1a",
                        "on-background": "#F9F9F9",
                        "inverse-surface": "#F9F9F9",
                        "surface-tint": "#c6c6c6",
                        "background": "#121212",
                        "surface": "#121212",
                        "primary": "#F9F9F9",
                        "on-primary": "#121212",
                        "outline": "#444444"
                    },
                    "borderRadius": {
                        "DEFAULT": "0px",
                        "lg": "0px",
                        "xl": "0px",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "24px",
                        "container-max": "1440px",
                        "stack-xl": "120px",
                        "gutter": "32px",
                        "stack-sm": "8px",
                        "margin-desktop": "80px",
                        "stack-md": "24px",
                        "unit": "4px",
                        "stack-lg": "64px"
                    },
                    "fontFamily": {
                        "display": ["Playfair Display", "serif"],
                        "sans": ["Hanken Grotesk", "sans-serif"],
                        "headline-lg": ["Playfair Display"],
                        "button": ["Hanken Grotesk"],
                        "display-lg-mobile": ["Playfair Display"],
                        "display-lg": ["Playfair Display"],
                        "headline-md": ["Playfair Display"],
                        "body-lg": ["Hanken Grotesk"],
                        "body-md": ["Hanken Grotesk"],
                        "label-caps": ["Hanken Grotesk"]
                    }
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 200, 'GRAD' 0, 'opsz' 24;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            background-color: #121212;
            color: #F9F9F9;
            -webkit-font-smoothing: antialiased;
            scrollbar-width: thin;
            scrollbar-color: #444 #121212;
            font-family: 'Hanken Grotesk', sans-serif;
        }
        .scroll-container {
            width: 100%;
            overflow-x: hidden;
        }
        .reveal {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
    </style>
    @yield('styles')
</head>
<body class="overflow-x-hidden">

    <!-- Cart Notification / Toast -->
    <div id="cart-toast" class="fixed bottom-8 right-8 bg-[#F9F9F9] text-[#121212] px-6 py-4 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none flex items-center gap-4 shadow-2xl border border-white/10">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <span class="label-tiny font-bold" id="toast-message">Item added to bag</span>
    </div>

    <!-- Navigation Bar -->
    @include('components.navbar')

    <!-- Main Content Area -->
    <div class="scroll-container">
        @yield('content')
        
        <!-- Footer -->
        @include('components.footer')
    </div>

    <!-- Global Layout Scripts -->
    <script>
        // Toggle Mobile Navigation Drawer
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            if (menu) {
                if (menu.classList.contains('translate-x-full')) {
                    menu.classList.remove('translate-x-full');
                    menu.classList.add('translate-x-0');
                    document.body.classList.add('overflow-hidden');
                } else {
                    menu.classList.remove('translate-x-0');
                    menu.classList.add('translate-x-full');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        }

        // Dynamic Navbar styling on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            const navContainer = document.getElementById('main-nav-container');
            if (nav && navContainer) {
                if (window.scrollY > 50) {
                    nav.classList.remove('bg-transparent', 'border-transparent');
                    nav.classList.add('bg-[#121212]/90', 'backdrop-blur-md', 'shadow-2xl', 'border-b', 'border-[#F9F9F9]/10');
                    navContainer.classList.remove('py-8');
                    navContainer.classList.add('py-4');
                } else {
                    nav.classList.remove('bg-[#121212]/90', 'backdrop-blur-md', 'shadow-2xl', 'border-b', 'border-[#F9F9F9]/10');
                    nav.classList.add('bg-transparent', 'border-transparent');
                    navContainer.classList.remove('py-4');
                    navContainer.classList.add('py-8');
                }
            }
        });

        // LocalStorage Cart Helper Functions
        function getCart() {
            return JSON.parse(localStorage.getItem('cart')) || [];
        }

        function saveCart(cart) {
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        function updateCartCount() {
            const cart = getCart();
            const totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity || 1), 0);
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) {
                if (totalItems > 0) {
                    cartCountEl.textContent = totalItems;
                    cartCountEl.classList.remove('hidden');
                } else {
                    cartCountEl.classList.add('hidden');
                }
            }
        }

        function showToast(message) {
            const toast = document.getElementById('cart-toast');
            const msgEl = document.getElementById('toast-message');
            if (toast && msgEl) {
                msgEl.textContent = message;
                toast.classList.remove('translate-y-20', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
                
                setTimeout(() => {
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        }

        // Global Add To Bag function for landing/catalog page
        function addToBag(id, name, price, size = '100gr') {
            let cart = getCart();
            let existingItem = cart.find(item => item.id === id && item.grind_size === size);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    grind_size: size,
                    quantity: 1,
                    image: "https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w"
                });
            }
            
            saveCart(cart);
            showToast(`"${size} - ${name}" ditambahkan.`);
        }

        // Scroll Reveal Observer
        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();

            const revealElements = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            });

            revealElements.forEach(el => observer.observe(el));
        });
    </script>
    @yield('scripts')
</body>
</html>
