<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Admin Dashboard | Toko Kopi Sembilan')</title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100;300;400;700;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-container": "#F9F9F9",
                        "on-background": "#121212",
                        "inverse-surface": "#121212",
                        "surface-tint": "#121212",
                        "background": "#FFFFFF",
                        "surface": "#FFFFFF",
                        "primary": "#121212",
                        "on-primary": "#FFFFFF",
                        "outline": "#E5E7EB",
                        "brand-dark": "#121212",
                        "brand-accent": "#121212",
                        "brand-cream": "#F9F9F9",
                        "brand-light": "#FFFFFF",
                        "brand-gray": "#F9F9F9",
                        "brand-outline": "#E5E7EB"
                    },
                    "borderRadius": {
                        "DEFAULT": "0px",
                        "lg": "0px",
                        "xl": "0px",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "display": ["Playfair Display", "serif"],
                        "sans": ["Hanken Grotesk", "sans-serif"]
                    }
                }
            }
        }
    </script>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 200, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #FFFFFF;
            color: #121212;
            -webkit-font-smoothing: antialiased;
            scrollbar-width: thin;
            scrollbar-color: #E5E7EB #FFFFFF;
            font-family: 'Hanken Grotesk', sans-serif;
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
        .order-row {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .order-row:hover {
            background-color: #F9F9F9;
        }
        input, select {
            background: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            color: #121212 !important;
            border-radius: 0px !important;
        }
        input:focus, select:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #121212 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar Navigation -->
        <aside id="admin-sidebar" class="w-64 flex-shrink-0 border-r border-[#E5E7EB] flex flex-col justify-between h-full bg-[#FFFFFF] transition-all duration-300">
            <div>
                <div class="h-20 px-8 border-b border-[#E5E7EB] flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-4 sidebar-brand">
                        <img alt="Toko Kopi Sembilan Logogram" class="h-8 w-auto min-w-[32px]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9MpVXkkX9y3Q_Se_xUanS6w5p15bpo-B_mSEXVouJ0-V4stob2SLDcZ6bWOAJYDWK0fRxeiroHyw9gyc6lQ4wqWjNQVopzVTVzavgA9q4G_Jns1je7kyRMW9YZ8Pnm15y4fyR0LH8MTgR8r1_qhAsptFz0lEtXTkHQSYk8XazLz62-PN057iWfrwBk7eVgsTC1VvA0TDbXPbaKXpLPfLNNriXU2Oqpl9NhJc7ZOJNFjG0LIT3s4SDSER5NlbOjdVgl_TyBjuq3AU"/>
                        <span class="label-tiny font-semibold text-[#121212] sidebar-text transition-all duration-300">Toko Kopi 9</span>
                    </a>
                </div>
                <nav class="mt-8">
                    <ul class="space-y-1">
                        <li>
                            <button onclick="switchAdminTab('orders')" id="sidebar-tab-orders" class="w-full flex items-center gap-4 px-8 py-4 bg-[#121212] text-[#FFFFFF] font-semibold transition-all duration-150 label-tiny sidebar-item">
                                <span class="material-symbols-outlined text-lg">shopping_cart</span>
                                <span class="sidebar-text transition-all duration-300">Orders</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="switchAdminTab('inventory')" id="sidebar-tab-inventory" class="w-full flex items-center gap-4 px-8 py-4 text-[#121212]/60 hover:text-[#121212] hover:bg-[#F9F9F9] transition-all duration-150 label-tiny sidebar-item">
                                <span class="material-symbols-outlined text-lg">inventory_2</span>
                                <span class="sidebar-text transition-all duration-300">Inventory</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="switchAdminTab('customers')" id="sidebar-tab-customers" class="w-full flex items-center gap-4 px-8 py-4 text-[#121212]/60 hover:text-[#121212] hover:bg-[#F9F9F9] transition-all duration-150 label-tiny sidebar-item">
                                <span class="material-symbols-outlined text-lg">group</span>
                                <span class="sidebar-text transition-all duration-300">Customers</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="switchAdminTab('analytics')" id="sidebar-tab-analytics" class="w-full flex items-center gap-4 px-8 py-4 text-[#121212]/60 hover:text-[#121212] hover:bg-[#F9F9F9] transition-all duration-150 label-tiny sidebar-item">
                                <span class="material-symbols-outlined text-lg">analytics</span>
                                <span class="sidebar-text transition-all duration-300">Analytics</span>
                            </button>
                        </li>
                        <li>
                            <button onclick="switchAdminTab('couriers')" id="sidebar-tab-couriers" class="w-full flex items-center gap-4 px-8 py-4 text-[#121212]/60 hover:text-[#121212] hover:bg-[#F9F9F9] transition-all duration-150 label-tiny sidebar-item">
                                <span class="material-symbols-outlined text-lg">local_shipping</span>
                                <span class="sidebar-text transition-all duration-300">Couriers</span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="p-8 border-t border-[#E5E7EB] flex flex-col gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-4 text-[#121212]/60 hover:text-[#121212] transition-colors sidebar-footer-item">
                    <span class="material-symbols-outlined">home</span>
                    <span class="label-tiny sidebar-text transition-all duration-300">View Store</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-4 text-[#121212]/60 hover:text-red-600 transition-colors sidebar-footer-item">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="label-tiny sidebar-text transition-all duration-300">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow flex flex-col h-full bg-[#FFFFFF]">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const isExpanded = sidebar.classList.contains('w-64');
            
            if (isExpanded) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                // Sembunyikan teks
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => el.classList.add('hidden'), 200);
                });
                // Ubah alignment item di sidebar menjadi memusat
                document.querySelectorAll('.sidebar-item, .sidebar-footer-item').forEach(el => {
                    el.classList.remove('px-8');
                    el.classList.add('justify-center', 'px-0');
                });
                // Sembunyikan spacer/logo di brand
                const brand = document.querySelector('.sidebar-brand');
                brand.classList.remove('gap-4');
                brand.classList.add('justify-center');
            } else {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                // Tampilkan teks
                document.querySelectorAll('.sidebar-text').forEach(el => {
                    el.classList.remove('hidden');
                    setTimeout(() => el.classList.remove('opacity-0', 'pointer-events-none'), 50);
                });
                // Kembalikan padding
                document.querySelectorAll('.sidebar-item, .sidebar-footer-item').forEach(el => {
                    el.classList.remove('justify-center', 'px-0');
                    el.classList.add('px-8');
                });
                // Kembalikan brand
                const brand = document.querySelector('.sidebar-brand');
                brand.classList.remove('justify-center');
                brand.classList.add('gap-4');
            }
        }

        function switchAdminTab(tabName) {
            // Jika fungsi switchTab ada (di halaman dashboard)
            if (typeof switchTab === 'function') {
                switchTab(tabName);
            } else {
                window.location.href = "{{ route('admin.dashboard') }}?tab=" + tabName;
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
