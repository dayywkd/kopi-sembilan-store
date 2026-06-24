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
    input[type="text"], input[type="email"], select, textarea {
        background: transparent !important;
        border: 1px solid #d1d5db !important;
        color: #121212 !important;
        border-radius: 0px !important;
    }
    input[type="text"]:focus, input[type="email"]:focus, select:focus, textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #121212 !important;
    }
    /* Style for dropdown options to make sure text is readable in select */
    select option {
        background-color: #ffffff !important;
        color: #121212 !important;
    }
    /* Guarantee visibility of text inside checked radio cards */
    input[type="radio"]:checked + div {
        background-color: #121212 !important;
        color: #ffffff !important;
        border-color: transparent !important;
    }
    input[type="radio"]:checked + div,
    input[type="radio"]:checked + div * {
        color: #ffffff !important;
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
    <div class="grid grid-cols-12 gap-gutter">
        <!-- Left Side: Shipping & Payment -->
        <div class="col-span-12 lg:col-span-8">
            <div class="mb-stack-lg border-b border-[#E5E7EB] pb-6">
                <h1 class="font-display text-5xl md:text-7xl uppercase italic mb-2 text-[#121212]">Checkout</h1>
                <p class="label-tiny text-neutral-500 font-semibold">Complete your shipping and payment</p>
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
                    <div class="border-b border-[#E5E7EB] pb-2 flex items-center justify-between text-[#121212]">
                        <h2 class="label-tiny text-neutral-500 font-semibold">01 / Shipping Details</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @auth
                            @php
                                $names = explode(' ', auth()->user()->name, 2);
                                $firstName = $names[0] ?? '';
                                $lastName = $names[1] ?? 'Customer';
                            @endphp
                            <input type="hidden" name="first_name" id="first-name" value="{{ $firstName }}">
                            <input type="hidden" name="last_name" id="last-name" value="{{ $lastName }}">
                            <input type="hidden" name="email" id="email" value="{{ auth()->user()->email }}">
                        @else
                            <!-- Guest Choice Banner -->
                            <div class="col-span-full bg-brand-cream border border-neutral-200 p-6 mb-2 flex flex-col md:flex-row md:items-center justify-between gap-4 font-sans text-[#121212]">
                                <div>
                                    <h4 class="font-display text-sm uppercase italic font-bold">Checkout as Guest / Tamu</h4>
                                    <p class="text-xs text-neutral-500 mt-1">Silakan isi formulir di bawah untuk memesan langsung, atau masuk log untuk kemudahan otomatis.</p>
                                </div>
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('checkout')) }}" class="px-5 py-2.5 bg-[#121212] text-white font-semibold text-xs uppercase tracking-wider hover:bg-brand-accent hover:border-brand-accent hover:text-white border border-transparent transition-all text-center duration-300">
                                    Masuk Log / Login
                                </a>
                            </div>

                            <!-- Visible Name & Email Inputs for Guests -->
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
                        @endauth
                        
                        <div class="col-span-full flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Phone Number / Nomor HP</label>
                            <input id="phone" name="phone" value="{{ auth()->check() ? (auth()->user()->phone ?? old('phone')) : old('phone') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40 font-sans" placeholder="08XXXXXXXXXX" required type="text">
                            @error('phone')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Delivery Method Selector -->
                        <div class="col-span-full flex flex-col gap-3 mb-4">
                            <label class="label-tiny text-[10px] text-neutral-500 font-semibold">Metode Penerimaan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="cursor-pointer relative">
                                    <input class="hidden peer" name="delivery_method" type="radio" value="shipping" checked onchange="toggleDeliveryMethod('shipping')">
                                    <div class="border border-neutral-300 p-5 flex flex-col gap-1 peer-checked:bg-[#121212] peer-checked:text-white peer-checked:border-transparent transition-all">
                                        <div class="flex justify-between items-start">
                                            <span class="label-tiny font-bold text-xs">Kirim via Kurir</span>
                                            <span class="material-symbols-outlined text-[20px]">local_shipping</span>
                                        </div>
                                        <p class="text-[10px] opacity-70">Pengiriman JNE Reguler / J&T ke alamat Anda</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer relative">
                                    <input class="hidden peer" name="delivery_method" type="radio" value="pickup" onchange="toggleDeliveryMethod('pickup')">
                                    <div class="border border-neutral-300 p-5 flex flex-col gap-1 peer-checked:bg-[#121212] peer-checked:text-white peer-checked:border-transparent transition-all">
                                        <div class="flex justify-between items-start">
                                            <span class="label-tiny font-bold text-xs">Ambil di Toko (Local Pickup)</span>
                                            <span class="material-symbols-outlined text-[20px]">store</span>
                                        </div>
                                        <p class="text-[10px] opacity-70">Ambil langsung di Roastery (Tuban, Jawa Timur)</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Address field container -->
                        <div id="address-field-container" class="col-span-full flex flex-col gap-2">
                            <label class="label-tiny text-[10px] opacity-60">Shipping Address</label>
                            <textarea id="address" name="address" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20 resize-none min-h-[80px]" placeholder="STREET, BUILDING, UNIT" required>{{ auth()->check() ? (auth()->user()->address ?? old('address')) : old('address') }}</textarea>
                            @error('address')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Area search field container -->
                        <div id="area-search-field-container" class="col-span-full flex flex-col gap-2 relative text-[#121212]">
                            <label class="label-tiny text-[10px] text-neutral-500 font-semibold">Cari Wilayah (Kecamatan, Kota, atau Kode Pos)</label>
                            <input id="area-search" type="text" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40 bg-[#FFFFFF] border border-[#E5E7EB] text-[#121212] focus:border-brand-accent transition-all font-sans" placeholder="Ketik minimal 3 karakter untuk mencari wilayah..." autocomplete="off" value="{{ auth()->check() ? (auth()->user()->biteship_area_name ?? '') : '' }}" required>
                            <div id="area-suggestions" class="absolute left-0 right-0 top-[100%] z-50 bg-[#FFFFFF] border border-[#E5E7EB] divide-y divide-[#E5E7EB] max-h-60 overflow-y-auto hidden shadow-lg">
                                <!-- suggestions populated dynamically -->
                            </div>
                            <input type="hidden" name="biteship_area_id" id="biteship-area-id" value="{{ auth()->check() ? (auth()->user()->biteship_area_id ?? old('biteship_area_id')) : old('biteship_area_id') }}">
                            <input type="hidden" name="biteship_area_name" id="biteship-area-name" value="{{ auth()->check() ? (auth()->user()->biteship_area_name ?? old('biteship_area_name')) : old('biteship_area_name') }}">
                            @error('biteship_area_id')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Hidden inputs for city and postal code, populated automatically by Biteship area selection -->
                        <input id="city" name="city" value="{{ auth()->check() ? (auth()->user()->city ?? old('city')) : old('city') }}" type="hidden">
                        <input id="postal-code" name="postal_code" value="{{ auth()->check() ? (auth()->user()->postal_code ?? old('postal_code')) : old('postal_code') }}" type="hidden">

                        <!-- Hidden input for courier (selected courier code) -->
                        <input type="hidden" name="courier" id="courier" value="{{ old('courier') }}">

                        <div class="col-span-full flex flex-col gap-3 text-[#121212]" id="shipping-service-container" style="display: none;">
                            <label class="label-tiny text-[10px] text-neutral-500 font-semibold">Pilih Layanan Pengiriman</label>
                            <div id="shipping-service-options" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Radio cards populated dynamically -->
                            </div>
                            <input type="hidden" name="shipping_service" id="shipping_service" value="{{ old('shipping_service') }}">
                            <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost') }}">
                            @error('shipping_service')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                            @error('shipping_cost')
                                <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </section>

                <!-- Section 2: Payment Method -->
                <section class="space-y-6">
                    <div class="border-b border-neutral-200 pb-2">
                        <h2 class="label-tiny opacity-60">02 / Payment Method</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[#121212]">
                        <label class="cursor-pointer relative">
                            <input class="hidden peer" name="payment" type="radio" value="Bank Transfer" {{ old('payment', 'Bank Transfer') === 'Bank Transfer' ? 'checked' : '' }}>
                            <div class="border border-neutral-300 p-6 flex flex-col gap-2 peer-checked:bg-[#121212] peer-checked:text-white peer-checked:border-transparent transition-all">
                                <div class="flex justify-between items-start">
                                    <span class="label-tiny font-bold">Bank Transfer</span>
                                    <span class="material-symbols-outlined text-[20px]">account_balance</span>
                                </div>
                                <p class="text-[11px] opacity-70">Verifikasi manual dalam 24 jam via Bank Transfer</p>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input class="hidden peer" name="payment" type="radio" value="QRIS" {{ old('payment') === 'QRIS' ? 'checked' : '' }}>
                            <div class="border border-neutral-300 p-6 flex flex-col gap-2 peer-checked:bg-[#121212] peer-checked:text-white peer-checked:border-transparent transition-all">
                                <div class="flex justify-between items-start">
                                    <span class="label-tiny font-bold">QRIS / Instant</span>
                                    <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                                </div>
                                <p class="text-[11px] opacity-70">Proses instan &amp; otomatis terverifikasi</p>
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
            <div class="border border-neutral-200 p-8 bg-brand-cream shadow-sm text-[#121212]">
                <h3 class="label-tiny opacity-60 border-b border-neutral-200 pb-4 mb-6 font-bold">Order Summary</h3>
                
                <!-- Dynamic Items List -->
                <div id="summary-items" class="space-y-6 mb-6">
                    <!-- Injected dynamically -->
                </div>

                <div class="pt-6 border-t border-neutral-200 space-y-4 text-sm font-sans text-neutral-600">
                    <div class="flex justify-between">
                        <span class="opacity-60">Subtotal</span>
                        <span id="summary-subtotal" class="font-semibold text-[#121212]">Rp 0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="opacity-60">Shipping / Ongkir</span>
                        <span id="summary-shipping" class="font-semibold text-[#121212]">Rp 0</span>
                    </div>
                    


                    <div class="flex justify-between font-display text-2xl border-t border-neutral-200 pt-6 uppercase text-[#121212]">
                        <span>Total</span>
                        <span id="summary-total" class="font-bold">Rp 0</span>
                    </div>
                </div>

                <div id="api-error-alert" class="bg-brand-cream border border-brand-accent/20 text-brand-accent px-4 py-3 mt-6 text-[11px] uppercase tracking-wide hidden">
                    Layanan pengiriman otomatis sedang tidak tersedia. Silakan klik tombol "Pesan via WhatsApp" untuk menyelesaikan pesanan Anda.
                </div>

                <div id="checkout-actions-container">
                    <button id="pay-button" form="checkout-form" type="submit" class="w-full mt-8 bg-[#121212] text-white font-bold text-center py-5 uppercase tracking-widest hover:bg-brand-accent hover:border-brand-accent hover:text-white border border-[#121212] transition-all duration-350 active:scale-[0.98] label-tiny">
                        Confirm &amp; Pay
                    </button>
                    <a id="whatsapp-fallback-button" href="#" target="_blank" class="w-full mt-8 bg-[#25D366] text-[#FFFFFF] font-bold text-center py-5 uppercase tracking-widest hover:bg-[#128C7E] border border-transparent transition-all duration-350 active:scale-[0.98] label-tiny hidden flex items-center justify-center gap-2">
                        <svg class="fill-current" style="width:20px; height:20px;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.498 1.452 5.43 1.453 5.414 0 9.82-4.402 9.823-9.817.002-2.624-1.02-5.09-2.88-6.953-1.862-1.863-4.33-2.887-6.954-2.889-5.422 0-9.827 4.404-9.831 9.82-.001 1.943.506 3.841 1.47 5.509l-.965 3.526 3.61-.947zm11.231-6.793c-.302-.152-1.791-.883-2.073-.985-.282-.102-.489-.152-.696.152-.207.304-.799.985-.979 1.187-.18.203-.361.228-.663.077-1.127-.565-1.928-1.01-2.697-2.327-.2-.343.2-.319.574-1.066.06-.122.03-.228-.015-.319-.045-.091-.489-1.18-.671-1.616-.177-.428-.356-.369-.489-.376-.127-.007-.272-.008-.418-.008-.145 0-.382.054-.582.273-.2.22-.763.746-.763 1.82 0 1.073.782 2.107.891 2.254.11.147 1.54 2.349 3.729 3.291.52.224.926.358 1.242.459.522.167 1.002.143 1.379.087.42-.063 1.291-.527 1.472-1.034.18-.506.18-.94.127-1.034-.053-.09-.203-.152-.505-.304z"/></svg>
                        Pesan via WhatsApp
                    </a>
                </div>
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
    let globalSubtotal = 0;

    function formatRupiah(num) {
        return 'Rp. ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function renderSummary(customShippingCost = null) {
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
        globalSubtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            globalSubtotal += itemTotal;
            const grindLabel = item.grind_size ? item.grind_size : 'WHOLE BEAN';
            
            html += `
                <div class="flex gap-4 items-center text-[#121212]">
                    <div class="w-16 h-20 bg-brand-cream border border-[#E5E7EB] overflow-hidden flex-shrink-0">
                        <img class="w-full h-full object-cover" src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="flex-grow flex flex-col justify-between">
                        <div>
                            <h4 class="font-display text-sm uppercase italic leading-tight text-[#121212]">${item.name}</h4>
                            <p class="label-tiny text-[9px] text-neutral-400 mt-1">${grindLabel} | QTY: ${item.quantity}</p>
                        </div>
                        <span class="font-sans text-xs font-semibold mt-2 text-neutral-800">${formatRupiah(itemTotal)}</span>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('summary-subtotal').textContent = formatRupiah(globalSubtotal);
        
        let shipping = 0;
        if (customShippingCost !== null) {
            shipping = customShippingCost;
        }
        
        const total = globalSubtotal + shipping;
        document.getElementById('summary-shipping').textContent = customShippingCost !== null ? (shipping === 0 ? "FREE" : formatRupiah(shipping)) : "Rp. 0";
        document.getElementById('summary-total').textContent = formatRupiah(total);
    }

    function updateWhatsappLink() {
        const firstName = document.getElementById('first-name')?.value || '';
        const lastName = document.getElementById('last-name')?.value || '';
        const email = document.getElementById('email')?.value || '';
        const phone = document.getElementById('phone')?.value || '';
        const address = document.getElementById('address')?.value || '';
        const cityName = document.getElementById('city')?.value || '';
        const postalCode = document.getElementById('postal-code')?.value || '';
        
        const methodRadios = document.getElementsByName('delivery_method');
        let deliveryMethod = 'shipping';
        for (let radio of methodRadios) {
            if (radio.checked) {
                deliveryMethod = radio.value;
                break;
            }
        }

        let courierCode = document.getElementById('courier')?.value || '';
        let courierName = courierCode ? courierCode.toUpperCase() : 'Cheapest Courier';
        if (deliveryMethod === 'pickup') {
            courierName = 'AMBIL DI TOKO (LOCAL PICKUP)';
        }
        
        const paymentRadios = document.getElementsByName('payment');
        let paymentMethod = 'Bank Transfer';
        for (let radio of paymentRadios) {
            if (radio.checked) {
                paymentMethod = radio.value;
                break;
            }
        }
        
        const orderNotes = localStorage.getItem('order_notes') || '';
        
        const cart = getCart();
        let cartText = '';
        let subtotal = 0;
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            const grindLabel = item.grind_size ? item.grind_size : 'WHOLE BEAN';
            cartText += `- ${item.name} (${grindLabel}) x ${item.quantity} = ${formatRupiah(itemTotal)}\n`;
        });
        
        let receiptInfoText = '';
        if (deliveryMethod === 'pickup') {
            receiptInfoText = `Penerimaan: Ambil Mandiri di Toko (Tuban, Jawa Timur)\n`;
        } else {
            receiptInfoText = `Alamat: ${address}\n` +
                              `Kota: ${cityName}\n` +
                              `Kode Pos: ${postalCode}\n` +
                              `Pilihan Kurir: ${courierName}\n`;
        }

        const footerText = deliveryMethod === 'pickup' 
            ? `Saya akan mengambil pesanan langsung di roastery. Terima kasih!`
            : `Mohon bantu hitungkan ongkos kirim ke alamat saya. Terima kasih!`;

        const message = `Halo Toko Kopi Sembilan, saya ingin memesan:\n` +
                        `${cartText}` +
                        `Subtotal: ${formatRupiah(subtotal)}\n\n` +
                        `Detail Penerima:\n` +
                        `Nama: ${firstName} ${lastName}\n` +
                        `Email: ${email}\n` +
                        `Nomor HP: ${phone}\n` +
                        receiptInfoText +
                        `Metode Pembayaran: ${paymentMethod}\n` +
                        `Catatan: ${orderNotes}\n\n` +
                        footerText;
                        
        const phoneNumber = '6285855180131';
        const waUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        
        const waButton = document.getElementById('whatsapp-fallback-button');
        if (waButton) {
            waButton.href = waUrl;
        }
    }

    function enableWhatsappFallback() {
        const alertBox = document.getElementById('api-error-alert');
        if (alertBox) alertBox.classList.remove('hidden');
        
        const payBtn = document.getElementById('pay-button');
        if (payBtn) payBtn.classList.add('hidden');
        
        const waBtn = document.getElementById('whatsapp-fallback-button');
        if (waBtn) waBtn.classList.remove('hidden');
        
        const serviceContainer = document.getElementById('shipping-service-container');
        if (serviceContainer) serviceContainer.style.display = 'none';

        updateWhatsappLink();
    }

    function disableWhatsappFallback() {
        const alertBox = document.getElementById('api-error-alert');
        if (alertBox) alertBox.classList.add('hidden');
        
        const payBtn = document.getElementById('pay-button');
        if (payBtn) payBtn.classList.remove('hidden');
        
        const waBtn = document.getElementById('whatsapp-fallback-button');
        if (waBtn) waBtn.classList.add('hidden');
    }

    function checkAndFetchShippingCost() {
        const methodRadios = document.getElementsByName('delivery_method');
        let deliveryMethod = 'shipping';
        for (let radio of methodRadios) {
            if (radio.checked) {
                deliveryMethod = radio.value;
                break;
            }
        }

        if (deliveryMethod === 'pickup') {
            document.getElementById('shipping-service-container').style.display = 'none';
            document.getElementById('courier').value = 'pickup';
            document.getElementById('shipping_service').value = 'pickup';
            document.getElementById('shipping_cost').value = '0';
            renderSummary(0);
            return;
        }

        const areaId = document.getElementById('biteship-area-id').value;
        const cart = getCart();

        if (!areaId || cart.length === 0) {
            document.getElementById('shipping-service-container').style.display = 'none';
            document.getElementById('courier').value = '';
            document.getElementById('shipping_service').value = '';
            document.getElementById('shipping_cost').value = '';
            renderSummary(0);
            return;
        }

        const optionsDiv = document.getElementById('shipping-service-options');
        optionsDiv.innerHTML = '<p class="col-span-full text-xs opacity-60 uppercase tracking-widest animate-pulse">Menghitung tarif pengiriman...</p>';
        document.getElementById('shipping-service-container').style.display = 'block';

        fetch('/api/shipping-cost', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                biteship_area_id: areaId,
                destination_postal_code: document.getElementById('postal-code')?.value || '',
                cart_data: JSON.stringify(cart)
            })
        })
        .then(response => response.json())
        .then(res => {
            if (res.success && res.rates && res.rates.length > 0) {
                disableWhatsappFallback();

                // Filter to keep JNE Reguler and J&T
                const filteredRates = res.rates.filter(rate => {
                    const courier = rate.courier.toLowerCase();
                    const service = rate.service.toLowerCase();
                    
                    if (courier === 'jne') {
                        return service === 'reguler' || service === 'reg';
                    }
                    if (courier === 'jnt' || courier === 'j&t') {
                        return service === 'ez' || service === 'reguler' || service === 'regular' || service === 'standard';
                    }
                    return false;
                });

                if (filteredRates.length === 0) {
                    optionsDiv.innerHTML = '<p class="col-span-full text-xs text-brand-accent uppercase tracking-widest">Tidak ada layanan JNE Reguler atau J&T yang menjangkau lokasi Anda.</p>';
                    enableWhatsappFallback();
                    return;
                }

                // Sort rates by cost ascending (cheapest first)
                const sortedRates = filteredRates.sort((a, b) => a.cost - b.cost);
                
                // Populate radio cards
                let optionsHtml = '';
                sortedRates.forEach((rate, index) => {
                    const isChecked = index === 0 ? 'checked' : '';
                    const courierName = rate.courier.toLowerCase() === 'jne' ? 'JNE Reguler' : 'J&T';
                    
                    // Parse and clean estimated delivery time
                    let etdText = '1-3 Hari';
                    if (rate.etd) {
                        let etdClean = rate.etd.toString().toLowerCase().replace(/days|day|hari/g, '').replace(/\s+/g, '');
                        if (etdClean) {
                            etdText = `${etdClean} Hari`;
                        }
                    }
                    
                    const valueStr = `${rate.courier}|${rate.service}|${rate.cost}`;
                    
                    optionsHtml += `
                        <label class="cursor-pointer relative block">
                            <input class="hidden peer" type="radio" name="shipping_rate_option" value="${valueStr}" ${isChecked} onchange="selectShippingRate('${rate.courier}', '${rate.service}', ${rate.cost})">
                            <div class="border border-neutral-300 p-5 flex justify-between items-center peer-checked:bg-[#121212] peer-checked:text-white peer-checked:border-transparent transition-all">
                                <div class="flex flex-col gap-1">
                                    <span class="label-tiny font-bold text-xs">${courierName}</span>
                                    <span class="text-[10px] opacity-70">Estimasi ${etdText}</span>
                                </div>
                                <span class="font-display font-semibold text-sm">${formatRupiah(rate.cost)}</span>
                            </div>
                        </label>
                    `;
                });
                optionsDiv.innerHTML = optionsHtml;

                // Auto-select the first (cheapest) rate
                const cheapestRate = sortedRates[0];
                document.getElementById('courier').value = cheapestRate.courier;
                document.getElementById('shipping_service').value = cheapestRate.service;
                document.getElementById('shipping_cost').value = cheapestRate.cost;

                renderSummary(cheapestRate.cost);
                updateWhatsappLink();
            } else {
                const errMsg = res.message || 'Tidak ada layanan kurir yang tersedia.';
                optionsDiv.innerHTML = `<p class="col-span-full text-xs text-neutral-400 uppercase tracking-widest">${errMsg}</p>`;
                console.error('Failed to calculate shipping:', errMsg);
                enableWhatsappFallback();
            }
        })
        .catch(err => {
            console.error('Error fetching shipping cost:', err);
            optionsDiv.innerHTML = '<p class="col-span-full text-xs text-neutral-400 uppercase tracking-widest">Gagal memuat tarif kurir.</p>';
            enableWhatsappFallback();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderSummary();

        // Autocomplete Area Search
        const areaSearchInput = document.getElementById('area-search');
        const suggestionsDiv = document.getElementById('area-suggestions');
        let areaSearchTimeout = null;

        if (areaSearchInput) {
            areaSearchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(areaSearchTimeout);
                
                if (query.length < 3) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                areaSearchTimeout = setTimeout(() => {
                    suggestionsDiv.innerHTML = '<div class="p-3 text-xs opacity-60">Mencari wilayah...</div>';
                    suggestionsDiv.classList.remove('hidden');

                    fetch(`/api/biteship/search-areas?input=${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(res => {
                            if (res.success && res.areas && res.areas.length > 0) {
                                let html = '';
                                res.areas.forEach(area => {
                                    const postalMatch = area.name.match(/\.\s*(\d+)$/);
                                    const postalCode = area.postal_code || (postalMatch ? postalMatch[1] : '');
                                    const displayName = area.name.match(/\d{5}$/) ? area.name : `${area.name} (${postalCode})`;
                                    html += `
                                        <div class="suggestion-item p-3 cursor-pointer border-b border-[#E5E7EB]/50 hover:bg-brand-cream text-[#121212] hover:text-brand-accent transition-colors text-xs font-sans" 
                                             data-id="${area.id}" 
                                             data-name="${displayName}"
                                             data-city="${area.administrative_division_level_2_name}"
                                             data-postal="${postalCode}">
                                             ${displayName}
                                        </div>
                                    `;
                                });
                                suggestionsDiv.innerHTML = html;

                                // Add click listeners to items
                                suggestionsDiv.querySelectorAll('.suggestion-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        const id = this.getAttribute('data-id');
                                        const name = this.getAttribute('data-name');
                                        const city = this.getAttribute('data-city');
                                        const postal = this.getAttribute('data-postal');

                                        document.getElementById('biteship-area-id').value = id;
                                        document.getElementById('biteship-area-name').value = name;
                                        areaSearchInput.value = name;
                                        document.getElementById('city').value = city;
                                        document.getElementById('postal-code').value = postal;

                                        suggestionsDiv.classList.add('hidden');
                                        checkAndFetchShippingCost();
                                    });
                                });
                            } else {
                                suggestionsDiv.innerHTML = '<div class="p-3 text-xs opacity-60 text-amber-500">Tidak ada wilayah yang cocok.</div>';
                            }
                        })
                        .catch(err => {
                            console.error('Error searching areas:', err);
                            suggestionsDiv.innerHTML = '<div class="p-3 text-xs opacity-60 text-red-500">Gagal memuat hasil.</div>';
                        });
                }, 300);
            });

            // Close suggestions dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!areaSearchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                    suggestionsDiv.classList.add('hidden');
                }
            });
        }

        // Global function for toggling delivery method (shipping vs pickup)
        window.toggleDeliveryMethod = function(method) {
            const addressFieldContainer = document.getElementById('address-field-container');
            const areaSearchFieldContainer = document.getElementById('area-search-field-container');
            const shippingServiceContainer = document.getElementById('shipping-service-container');
            
            const addressInput = document.getElementById('address');
            const areaSearchInput = document.getElementById('area-search');
            
            if (method === 'pickup') {
                if (addressFieldContainer) addressFieldContainer.style.display = 'none';
                if (areaSearchFieldContainer) areaSearchFieldContainer.style.display = 'none';
                if (shippingServiceContainer) shippingServiceContainer.style.display = 'none';
                
                if (addressInput) addressInput.removeAttribute('required');
                if (areaSearchInput) areaSearchInput.removeAttribute('required');
                
                document.getElementById('courier').value = 'pickup';
                document.getElementById('shipping_service').value = 'pickup';
                document.getElementById('shipping_cost').value = '0';
                
                renderSummary(0);
                disableWhatsappFallback();
            } else {
                if (addressFieldContainer) addressFieldContainer.style.display = 'block';
                if (areaSearchFieldContainer) areaSearchFieldContainer.style.display = 'block';
                
                if (addressInput) addressInput.setAttribute('required', 'required');
                if (areaSearchInput) areaSearchInput.setAttribute('required', 'required');
                
                checkAndFetchShippingCost();
            }
            updateWhatsappLink();
        };

        // Global function for selecting shipping rate card
        window.selectShippingRate = function(courier, service, cost) {
            document.getElementById('courier').value = courier;
            document.getElementById('shipping_service').value = service;
            document.getElementById('shipping_cost').value = cost;
            renderSummary(cost);
            updateWhatsappLink();
        };

        // Auto-trigger calculation if area ID is already pre-filled (e.g. from user profile or old input)
        const initialAreaId = document.getElementById('biteship-area-id')?.value;
        if (initialAreaId) {
            checkAndFetchShippingCost();
        }

        // Bind input events to dynamically update WhatsApp message draft
        const inputFields = ['first-name', 'last-name', 'email', 'phone', 'address', 'city', 'postal-code', 'area-search'];
        inputFields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateWhatsappLink);
            }
        });

        document.getElementsByName('payment').forEach(radio => {
            radio.addEventListener('change', function() {
                updateWhatsappLink();
            });
        });

        const waBtn = document.getElementById('whatsapp-fallback-button');
        if (waBtn) {
            waBtn.addEventListener('click', function(e) {
                updateWhatsappLink();
            });
        }

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

                // Check if shipping details are calculated (if not under WhatsApp fallback)
                const isWaActive = !document.getElementById('whatsapp-fallback-button').classList.contains('hidden');
                if (!isWaActive) {
                    const methodRadios = document.getElementsByName('delivery_method');
                    let deliveryMethod = 'shipping';
                    for (let radio of methodRadios) {
                        if (radio.checked) {
                            deliveryMethod = radio.value;
                            break;
                        }
                    }

                    if (deliveryMethod === 'shipping') {
                        const courier = document.getElementById('courier').value;
                        const service = document.getElementById('shipping_service').value;
                        const cost = document.getElementById('shipping_cost').value;
                        const areaId = document.getElementById('biteship-area-id').value;
                        if (!courier || !service || cost === "" || !areaId) {
                            e.preventDefault();
                            alert('Silakan pilih wilayah pengiriman dan layanan kurir yang valid.');
                            return;
                        }
                    }
                }
                
                document.getElementById('cart-data-input').value = JSON.stringify(cart);
                
                setTimeout(() => {
                    localStorage.removeItem('cart');
                    localStorage.removeItem('order_notes');
                }, 100);
            });
        }


    });
</script>
@endsection
