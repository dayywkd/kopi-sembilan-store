@extends('layouts.app')

@section('title', 'Your Bag | Toko Kopi Sembilan')

@section('styles')
<style>
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
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto bg-white text-[#121212]">
    <header class="mb-stack-lg">
        <h1 class="font-display text-5xl md:text-7xl uppercase italic mb-4">Your Bag</h1>
        <p class="label-tiny text-neutral-500">SHIPPING CALCULATED AT CHECKOUT</p>
    </header>
    
    <div class="grid grid-cols-12 gap-gutter items-start">
        <!-- Cart Items Table -->
        <div class="col-span-12 lg:col-span-8">
            <div class="w-full border-t border-[#E5E7EB]">
                <!-- Table Header -->
                <div class="hidden md:grid grid-cols-12 py-4 border-b border-[#E5E7EB] label-tiny text-neutral-500">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-3 text-center">Quantity</div>
                    <div class="col-span-3 text-right">Price</div>
                </div>
                
                <!-- Dynamic Cart Items Container -->
                <div id="cart-items-container">
                    <!-- Dynamic items injected here -->
                </div>
            </div>
            
            <!-- Note Section -->
            <div class="mt-stack-md">
                <label class="label-tiny block mb-2 text-neutral-500 font-semibold">Order Instructions</label>
                <textarea id="order-notes" class="w-full bg-[#FFFFFF] border border-[#E5E7EB] p-4 font-sans focus:ring-1 focus:ring-brand-accent focus:border-brand-accent min-h-[100px] outline-none placeholder:opacity-30 text-sm text-[#121212]" placeholder="ADD SPECIAL REQUESTS..."></textarea>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-span-12 lg:col-span-4 lg:sticky lg:top-28">
            <div class="border border-[#E5E7EB] p-8 bg-brand-cream shadow-sm">
                <h2 class="font-display text-3xl uppercase italic mb-stack-md">Summary</h2>
                <div class="space-y-4 font-sans border-b border-[#E5E7EB] pb-stack-md mb-stack-md text-sm text-neutral-700">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Subtotal</span>
                        <span id="cart-subtotal" class="font-semibold text-neutral-800">Rp. 0</span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Shipping</span>
                        <span>Calculated later</span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Tax</span>
                        <span>Included</span>
                    </div>
                </div>
                <div class="flex justify-between font-display text-2xl uppercase mb-stack-lg text-[#121212]">
                    <span>Total</span>
                    <span id="cart-total">Rp. 0</span>
                </div>
                
                <a id="checkout-btn" class="block w-full bg-[#121212] text-white font-bold text-center py-5 uppercase tracking-widest hover:bg-brand-accent hover:border-brand-accent hover:text-white border border-[#121212] transition-all duration-350 active:scale-[0.98] label-tiny" href="{{ route('checkout') }}">
                    Proceed to Checkout
                </a>
                
                <div class="mt-stack-md flex items-center justify-center space-x-2 text-neutral-400">
                    <span class="material-symbols-outlined text-base">verified_user</span>
                    <span class="label-tiny text-[10px]">Secure Transaction Encrypted</span>
                </div>
            </div>
            
            <div class="mt-stack-md border border-[#E5E7EB] p-4 bg-brand-cream text-[#121212]">
                <div class="flex items-center space-x-2">
                    <span class="material-symbols-outlined text-xl text-neutral-600">local_shipping</span>
                    <span class="label-tiny text-[10px] md:text-[11px] text-neutral-700">FREE DOMESTIC SHIPPING OVER RP. 500k</span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    window.serverCart = @json($serverCart ?? []);

    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        syncCartToServer(cart);
        renderCart();
    }

    function formatRupiah(num) {
        return 'Rp. ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function renderCart() {
        const cart = getCart();
        const container = document.getElementById('cart-items-container');
        const checkoutBtn = document.getElementById('checkout-btn');
        
        if (!container) return;

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="py-16 text-center">
                    <p class="font-sans text-neutral-500 mb-8 text-lg">Your bag is empty.</p>
                    <a href="{{ route('shop') }}" class="inline-block bg-[#121212] text-white px-8 py-4 font-bold label-tiny border border-[#121212] hover:bg-brand-accent hover:border-brand-accent transition-all">Explore Beans</a>
                </div>
            `;
            if (checkoutBtn) {
                checkoutBtn.classList.add('pointer-events-none', 'opacity-30');
            }
            document.getElementById('cart-subtotal').textContent = formatRupiah(0);
            document.getElementById('cart-total').textContent = formatRupiah(0);
            return;
        }

        if (checkoutBtn) {
            checkoutBtn.classList.remove('pointer-events-none', 'opacity-30');
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            // Pilihan gilingan kopi
            const grindLabel = item.grind_size ? item.grind_size : 'WHOLE BEAN';
            
            html += `
                <div class="grid grid-cols-12 py-6 border-b border-[#E5E7EB] items-center hover:bg-brand-cream transition-colors duration-75">
                    <div class="col-span-12 md:col-span-6 flex items-center space-x-6 mb-4 md:mb-0">
                        <div class="w-24 h-32 bg-brand-cream border border-[#E5E7EB] overflow-hidden flex-shrink-0">
                            <img class="w-full h-full object-cover" src="${item.image}" alt="${item.name}">
                        </div>
                        <div>
                            <h3 class="font-display text-xl uppercase italic text-[#121212]">${item.name}</h3>
                            <p class="label-tiny text-[10px] mt-1 text-neutral-400">${grindLabel}</p>
                            <button onclick="removeItem(${item.id}, '${grindLabel}')" class="mt-4 label-tiny text-[10px] border-b border-[#121212] text-[#121212] hover:text-brand-accent hover:border-brand-accent transition-all">REMOVE</button>
                        </div>
                    </div>
                    <div class="col-span-6 md:col-span-3 flex justify-start md:justify-center">
                        <div class="flex border border-[#E5E7EB] h-10 w-28 bg-[#FFFFFF] text-[#121212]">
                            <button class="flex-1 hover:bg-brand-accent hover:text-white transition-colors font-bold" onclick="changeQuantity(${item.id}, '${grindLabel}', -1)">-</button>
                            <span class="w-8 flex items-center justify-center font-sans text-sm font-semibold">${item.quantity}</span>
                            <button class="flex-1 hover:bg-brand-accent hover:text-white transition-colors font-bold" onclick="changeQuantity(${item.id}, '${grindLabel}', 1)">+</button>
                        </div>
                    </div>
                    <div class="col-span-6 md:col-span-3 text-right">
                        <span class="font-sans font-semibold text-neutral-800">${formatRupiah(itemTotal)}</span>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('cart-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('cart-total').textContent = formatRupiah(subtotal);
    }

    function changeQuantity(id, grindSize, change) {
        let cart = getCart();
        let item = cart.find(item => item.id === id && item.grind_size === grindSize);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                cart = cart.filter(i => !(i.id === id && i.grind_size === grindSize));
            }
            saveCart(cart);
        }
    }

    function removeItem(id, grindSize) {
        let cart = getCart();
        cart = cart.filter(item => !(item.id === id && item.grind_size === grindSize));
        saveCart(cart);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Load notes jika sebelumnya sudah diisi
        const notesArea = document.getElementById('order-notes');
        if (notesArea) {
            notesArea.value = localStorage.getItem('order_notes') || '';
            
            // Simpan otomatis ke local storage setiap kali mengetik
            notesArea.addEventListener('input', function() {
                localStorage.setItem('order_notes', notesArea.value);
            });
        }
        
        renderCart();
    });
</script>
@endsection
