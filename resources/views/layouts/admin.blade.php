<!DOCTYPE html>
<html class="dark" lang="en">
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
            background-color: #121212;
            color: #F9F9F9;
            -webkit-font-smoothing: antialiased;
            scrollbar-width: thin;
            scrollbar-color: #444 #121212;
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
            color: #121212;
        }
        .order-row:hover .text-neutral-400,
        .order-row:hover .opacity-60,
        .order-row:hover .material-symbols-outlined {
            color: #121212 !important;
            opacity: 1 !important;
        }
        input, select {
            background: transparent !important;
            border: 1px solid #444444 !important;
            color: #F9F9F9 !important;
            border-radius: 0px !important;
        }
        input:focus, select:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #F9F9F9 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="overflow-hidden">
    <div class="flex h-screen w-full">
        <!-- Sidebar Navigation -->
        <aside class="w-64 flex-shrink-0 border-r border-[#444444] flex flex-col justify-between h-full bg-[#121212]">
            <div>
                <div class="p-8 border-b border-[#444444]">
                    <a href="{{ route('home') }}" class="flex items-center gap-4">
                        <img alt="Toko Kopi Sembilan Logogram" class="h-8 w-auto mix-blend-difference" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9MpVXkkX9y3Q_Se_xUanS6w5p15bpo-B_mSEXVouJ0-V4stob2SLDcZ6bWOAJYDWK0fRxeiroHyw9gyc6lQ4wqWjNQVopzVTVzavgA9q4G_Jns1je7kyRMW9YZ8Pnm15y4fyR0LH8MTgR8r1_qhAsptFz0lEtXTkHQSYk8XazLz62-PN057iWfrwBk7eVgsTC1VvA0TDbXPbaKXpLPfLNNriXU2Oqpl9NhJc7ZOJNFjG0LIT3s4SDSER5NlbOjdVgl_TyBjuq3AU"/>
                        <span class="label-tiny mix-blend-difference font-semibold">Toko Kopi 9</span>
                    </a>
                </div>
                <nav class="mt-8">
                    <ul class="space-y-1">
                        <li>
                            <a class="flex items-center gap-4 px-8 py-4 {{ Route::is('admin.dashboard') ? 'bg-[#F9F9F9] text-[#121212] font-semibold' : 'text-[#F9F9F9]/60 hover:text-[#F9F9F9] hover:bg-[#1a1a1a]' }} transition-all duration-150 label-tiny" href="{{ route('admin.dashboard') }}">
                                <span class="material-symbols-outlined text-lg">shopping_cart</span>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-4 px-8 py-4 text-[#F9F9F9]/60 hover:text-[#F9F9F9] hover:bg-[#1a1a1a] transition-all duration-150 label-tiny" href="#">
                                <span class="material-symbols-outlined text-lg">inventory_2</span>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-4 px-8 py-4 text-[#F9F9F9]/60 hover:text-[#F9F9F9] hover:bg-[#1a1a1a] transition-all duration-150 label-tiny" href="#">
                                <span class="material-symbols-outlined text-lg">group</span>
                                <span>Customers</span>
                            </a>
                        </li>
                        <li>
                            <a class="flex items-center gap-4 px-8 py-4 text-[#F9F9F9]/60 hover:text-[#F9F9F9] hover:bg-[#1a1a1a] transition-all duration-150 label-tiny" href="#">
                                <span class="material-symbols-outlined text-lg">analytics</span>
                                <span>Analytics</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="p-8 border-t border-[#444444]">
                <a href="{{ route('home') }}" class="flex items-center gap-4 text-[#F9F9F9]/60 hover:text-[#F9F9F9] transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="label-tiny">Exit System</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow flex flex-col h-full bg-[#121212]">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
