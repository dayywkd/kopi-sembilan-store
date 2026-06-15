@extends('layouts.app')

@section('title', 'Checkout | Toko Kopi Sembilan')

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
    input[type="text"], input[type="email"], textarea {
        background: transparent !important;
        border: 1px solid #444444 !important;
        color: #F9F9F9 !important;
        border-radius: 0px !important;
    }
    input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #F9F9F9 !important;
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
    <div class="grid grid-cols-12 gap-gutter">
        <!-- Left Side: Shipping & Payment -->
        <div class="col-span-12 lg:col-span-8">
            <div class="mb-stack-lg border-b border-[#F9F9F9]/15 pb-6">
                <h1 class="font-display text-5xl md:text-7xl uppercase italic mb-2">Checkout</h1>
                <p class="label-tiny opacity-60">Complete your shipping and payment architecture</p>
            </div>
            
            <!-- Tampilkan error umum jika ada -->
            @if ($errors->has('error'))
                <div class="bg-red-900/40 border border-red-500 text-red-200 px-6 py-4 mb-8 text-sm uppercase tracking-wide">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <form id="checkout-form" action="{{ route('order.process') }}" method="POST" class="space-y-12">
                @csrf
                
                <!-- Hidden inputs untuk mentransfer data client-side ke server -->
                <input type="hidden" name="cart_data" id="cart-data-input">
                <input type="hidden" name="order_notes" id="order-notes-input">

                <!-- Section 1: Shipping Details -->
                <section class="space-y-6">
                    <div class="border-b border-[#F9F9F9]/10 pb-2 flex items-center justify-between">
                        <h2 class="label-tiny opacity-60">01 / Shipping Details</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">First Name</label>
                            <input id="first-name" name="first_name" value="{{ old('first_name') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="ENTER FIRST NAME" required type="text">
                            @error('first_name')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Last Name</label>
                            <input id="last-name" name="last_name" value="{{ old('last_name') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="ENTER LAST NAME" required type="text">
                            @error('last_name')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-span-full flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Email Address</label>
                            <input id="email" name="email" value="{{ old('email') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="EMAIL@DOMAIN.COM" required type="email">
                            @error('email')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="col-span-full flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Shipping Address</label>
                            <textarea id="address" name="address" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20 resize-none min-h-[80px]" placeholder="STREET, BUILDING, UNIT" required>{{ old('address') }}</textarea>
                            @error('address')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">City</label>
                            <input id="city" name="city" value="{{ old('city') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="TUBAN" required type="text">
                            @error('city')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Postal Code</label>
                            <input id="postal-code" name="postal_code" value="{{ old('postal_code') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="65115" required type="text">
                            @error('postal_code')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </section>

                <!-- Section 2: Payment Method -->
                <section class="space-y-6">
                    <div class="border-b border-[#F9F9F9]/10 pb-2">
                        <h2 class="label-tiny opacity-60">02 / Payment Method</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input class="hidden peer" name="payment" type="radio" value="Bank Transfer" {{ old('payment', 'Bank Transfer') === 'Bank Transfer' ? 'checked' : '' }}>
                            <div class="border border-[#444444] p-6 flex flex-col gap-2 peer-checked:bg-[#F9F9F9] peer-checked:text-[#121212] peer-checked:border-transparent transition-all">
                                <div class="flex justify-between items-start">
                                    <span class="label-tiny font-bold">Bank Transfer</span>
                                    <span class="material-symbols-outlined text-[20px]">account_balance</span>
                                </div>
                                <p class="text-[11px] opacity-70">Manual verification within 24 hours</p>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input class="hidden peer" name="payment" type="radio" value="QRIS" {{ old('payment') === 'QRIS' ? 'checked' : '' }}>
                            <div class="border border-[#444444] p-6 flex flex-col gap-2 peer-checked:bg-[#F9F9F9] peer-checked:text-[#121212] peer-checked:border-transparent transition-all">
                                <div class="flex justify-between items-start">
                                    <span class="label-tiny font-bold">QRIS / Instant</span>
                                    <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                                </div>
                                <p class="text-[11px] opacity-70">Immediate processing &amp; roast schedule</p>
                            </div>
                        </label>
                    </div>
                    @error('payment')
                        <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                    @enderror
                </section>
            </form>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="col-span-12 lg:col-span-4 lg:sticky lg:top-28">
            <div class="border border-[#F9F9F9]/10 p-8 bg-[#1a1a1a]/40 backdrop-blur-xl">
                <h3 class="label-tiny opacity-60 border-b border-[#F9F9F9]/10 pb-4 mb-6">Order Summary</h3>
                
                <!-- Dynamic Items List -->
                <div id="summary-items" class="space-y-6 mb-6">
                    <!-- Injected dynamically -->
                </div>

                <div class="pt-6 border-t border-[#F9F9F9]/10 space-y-4 text-sm font-sans">
                    <div class="flex justify-between">
                        <span class="opacity-60">Subtotal</span>
                        <span id="summary-subtotal" class="font-semibold">Rp. 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-60">Shipping</span>
                        <span id="summary-shipping" class="font-semibold">Rp. 0</span>
                    </div>
                    <div class="flex justify-between font-display text-2xl border-t border-[#F9F9F9]/15 pt-6 uppercase">
                        <span>Total</span>
                        <span id="summary-total">Rp. 0</span>
                    </div>
                </div>

                <button form="checkout-form" type="submit" class="w-full mt-8 bg-[#F9F9F9] text-[#121212] font-bold text-center py-5 uppercase tracking-widest hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all duration-350 active:scale-[0.98] label-tiny">
                    Confirm &amp; Pay
                </button>
                <p class="text-[10px] text-center opacity-40 uppercase tracking-tighter px-4 mt-4 leading-normal">
                    By confirming order, you agree to our roastery fulfillment timeline and shipping policy.
                </p>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    function formatRupiah(num) {
        return 'Rp. ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function renderSummary() {
        const cart = getCart();
        const container = document.getElementById('summary-items');
        if (!container) return;

        if (cart.length === 0) {
            container.innerHTML = `<p class="font-sans text-neutral-400 text-sm">No items in your bag.</p>`;
            document.getElementById('summary-subtotal').textContent = formatRupiah(0);
            document.getElementById('summary-shipping').textContent = formatRupiah(0);
            document.getElementById('summary-total').textContent = formatRupiah(0);
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            const grindLabel = item.grind_size ? item.grind_size : 'WHOLE BEAN';
            
            html += `
                <div class="flex gap-4 items-center">
                    <div class="w-16 h-20 bg-[#1a1a1a] border border-[#F9F9F9]/10 overflow-hidden flex-shrink-0">
                        <img class="w-full h-full object-cover grayscale brightness-90" src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="flex-grow flex flex-col justify-between">
                        <div>
                            <h4 class="font-display text-sm uppercase italic leading-tight">${item.name}</h4>
                            <p class="label-tiny text-[9px] opacity-50 mt-1 text-neutral-400">${grindLabel} | QTY: ${item.quantity}</p>
                        </div>
                        <span class="font-sans text-xs font-semibold mt-2">${formatRupiah(itemTotal)}</span>
                    </div>
                </div>
            `;
        });

        // Hitung ongkir (free shipping jika >= 500.000, jika tidak 15.000)
        // Nilai ini akan divalidasi ulang di backend demi keamanan
        const shipping = subtotal >= 500000 ? 0 : 15000;
        const total = subtotal + shipping;

        container.innerHTML = html;
        document.getElementById('summary-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('summary-shipping').textContent = shipping === 0 ? "FREE" : formatRupiah(shipping);
        document.getElementById('summary-total').textContent = formatRupiah(total);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderSummary();

        // Salin data order notes dari local storage ke input form sebelum checkout
        const orderNotesInput = document.getElementById('order-notes-input');
        if (orderNotesInput) {
            orderNotesInput.value = localStorage.getItem('order_notes') || '';
        }

        // Tangkap event submit untuk menyalin cart data ke input tersembunyi
        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const cart = getCart();
                if (cart.length === 0) {
                    e.preventDefault();
                    alert('Keranjang belanja Anda kosong. Tambahkan produk sebelum checkout.');
                    return;
                }
                
                // Isi input tersembunyi dengan string JSON dari local storage
                document.getElementById('cart-data-input').value = JSON.stringify(cart);
                
                // Hapus data cart & notes di local storage client setelah disubmit ke server
                // Catatan: kita hapus local storage di client agar keranjang langsung kosong saat redirect sukses
                setTimeout(() => {
                    localStorage.removeItem('cart');
                    localStorage.removeItem('order_notes');
                }, 100);
            });
        }
    });
</script>
@endsection
