@extends('layouts.app')

@section('title', 'Order Tracking — ' . $order->transaction_id . ' | Toko Kopi Sembilan')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }
    @media (min-width: 768px) {
        .label-tiny { font-size: 12px; letter-spacing: 0.25em; }
    }

    /* Timeline connector */
    .phase-connector {
        width: 1px;
        flex-shrink: 0;
    }
    .phase-connector.done      { background: rgba(249,249,249,0.5); }
    .phase-connector.active    { background: linear-gradient(to bottom, rgba(34,197,94,0.6), rgba(249,249,249,0.1)); }
    .phase-connector.pending   { background: rgba(249,249,249,0.12); }

    /* Phase dot */
    .phase-dot {
        width: 10px;
        height: 10px;
        flex-shrink: 0;
    }
    .phase-dot.done    { background: #F9F9F9; }
    .phase-dot.active  { background: #22c55e; animation: dot-pulse 1.8s ease-in-out infinite; }
    .phase-dot.pending { background: transparent; border: 1px solid #444; }

    @keyframes dot-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94, 0.5); }
        50%       { box-shadow: 0 0 0 6px rgba(34,197,94, 0); }
    }

    /* Current badge */
    @keyframes badge-blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.5; }
    }
    .current-badge {
        animation: badge-blink 2.5s ease-in-out infinite;
    }
</style>
@endsection

@section('content')
@php
    /**
     * Status urutan:
     * 0 = Awaiting Payment
     * 1 = Paid
     * 2 = Packing
     * 3 = Shipped
     * 4 = Delivered
     *
     * Cara baca per fase:
     *   'done'    = statusIndex > faseIndex  → sudah lewat
     *   'active'  = statusIndex === faseIndex → sedang berlangsung
     *   'pending' = statusIndex < faseIndex  → belum sampai
     */
    $statusOrder = [
        'Awaiting Payment' => 0,
        'Paid'             => 1,
        'Packing'          => 2,
        'Shipped'          => 3,
        'Delivered'        => 4,
    ];
    $currentIdx = $statusOrder[$order->status] ?? 0;

    // Helper closure: kembalikan class dot & connector berdasarkan indeks fase
    $phaseState = function(int $faseIdx) use ($currentIdx): string {
        if ($currentIdx > $faseIdx) return 'done';
        if ($currentIdx === $faseIdx) return 'active';
        return 'pending';
    };
@endphp

<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">

    @if (session('confirm_success'))
        <div class="mb-8 bg-emerald-950/60 border border-emerald-800 text-emerald-300 p-6 text-xs uppercase tracking-widest font-bold">
            {{ session('confirm_success') }}
        </div>
    @endif

    {{-- ── Header dinamis berdasarkan status ──────────────────── --}}
    <section class="mb-stack-lg border-b border-[#F9F9F9]/15 pb-8">
        <div class="grid grid-cols-12 items-end gap-4">
            <div class="col-span-12 md:col-span-8">
                @php
                    $headerLabel = match($order->status) {
                        'Awaiting Payment' => 'Menunggu Pembayaran',
                        'Paid'             => 'Pembayaran Diterima',
                        'Packing'          => 'Sedang Dikemas',
                        'Shipped'          => 'Dalam Pengiriman',
                        'Delivered'        => 'Pesanan Tiba',
                        default            => 'Order Confirmed',
                    };
                    $headerTitle = match($order->status) {
                        'Awaiting Payment' => 'Awaiting Payment',
                        'Paid'             => 'Paid — In Roast',
                        'Packing'          => 'Packing & Prep',
                        'Shipped'          => 'On The Way',
                        'Delivered'        => 'Delivered',
                        default            => 'Order Confirmed',
                    };
                    $headerColor = match($order->status) {
                        'Awaiting Payment' => 'text-amber-400',
                        'Paid'             => 'text-blue-400',
                        'Packing'          => 'text-yellow-400',
                        'Shipped'          => 'text-green-400',
                        'Delivered'        => 'text-emerald-400',
                        default            => 'text-[#F9F9F9]/60',
                    };
                @endphp
                <span class="label-tiny {{ $headerColor }} font-bold tracking-widest block mb-2">
                    ● &nbsp;{{ $headerLabel }} — #{{ $order->transaction_id }}
                </span>
                <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none">
                    {{ $headerTitle }}
                </h1>
            </div>
            <div class="col-span-12 md:col-span-4 md:text-right">
                <p class="font-sans text-neutral-400 text-sm max-w-xs md:ml-auto leading-relaxed">
                    @if ($order->status === 'Awaiting Payment')
                        Segera selesaikan pembayaran agar pesanan Anda diproses.
                    @elseif ($order->status === 'Paid')
                        Pembayaran terverifikasi. Kopi Anda sedang masuk jadwal roasting.
                    @elseif ($order->status === 'Packing')
                        Biji kopi sedang di-roast dan dikemas dengan presisi di Tuban Roastery.
                    @elseif ($order->status === 'Shipped')
                        Paket Anda telah diserahkan ke kurir dan sedang dalam perjalanan.
                    @else
                        Paket telah tiba. Nikmati kopi terbaik Anda! 
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- ── Banner Belum Bayar ───────────────────────────────────── --}}
    @if ($order->status === 'Awaiting Payment')
    <section class="mb-10">
        <div class="bg-amber-950/50 border border-amber-700/60 p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-amber-400 text-[28px] flex-shrink-0">pending_actions</span>
                <div>
                    <p class="label-tiny text-amber-300 mb-1">Pembayaran Diperlukan</p>
                    <p class="font-sans text-sm text-amber-200/80 leading-relaxed">
                        Pesanan belum dibayar. Lakukan pembayaran via <strong>{{ $order->payment_method }}</strong> agar pesanan segera diproses.
                    </p>
                </div>
            </div>
            <a href="{{ route('order.payment', $order->transaction_id) }}"
               class="flex-shrink-0 flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-[#121212] font-bold text-xs uppercase tracking-widest px-6 py-4 transition-colors duration-300 active:scale-[0.98] whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">payments</span>
                Bayar Sekarang
            </a>
        </div>
    </section>
    @elseif ($order->status === 'Shipped')
    <section class="mb-10">
        <div class="bg-blue-950/50 border border-blue-700/60 p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-blue-400 text-[28px] flex-shrink-0">local_shipping</span>
                <div>
                    <p class="label-tiny text-blue-300 mb-1">Paket Dalam Perjalanan</p>
                    <p class="font-sans text-sm text-blue-200/80 leading-relaxed">
                        Pesanan Anda sedang dikirim. Nomor Resi: <strong>{{ $order->tracking_number ?? '-' }}</strong>. Silakan konfirmasi jika barang sudah sampai.
                    </p>
                </div>
            </div>
            <form action="{{ route('order.confirm_delivery', $order->transaction_id) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                        class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white font-bold text-xs uppercase tracking-widest px-6 py-4 transition-colors duration-300 active:scale-[0.98] whitespace-nowrap cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                    Konfirmasi Pesanan Tiba
                </button>
            </form>
        </div>
    </section>
    @elseif ($order->status === 'Delivered')
    <section class="mb-10">
        <div class="bg-emerald-950/50 border border-emerald-700/60 p-6 flex items-center gap-5">
            <span class="material-symbols-outlined text-emerald-400 text-[32px]">verified</span>
            <div>
                <p class="label-tiny text-emerald-300 mb-1">Pesanan Selesai</p>
                <p class="font-sans text-sm text-emerald-200/80 leading-relaxed">
                    Paket telah tiba di tangan Anda. Terima kasih telah mempercayai <strong>Toko Kopi Sembilan</strong>. Selamat menikmati!
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- ── Main Grid ────────────────────────────────────────────── --}}
    <section class="grid grid-cols-12 gap-gutter">

        {{-- LEFT: Timeline Status ──────────────────────────────── --}}
        <div class="col-span-12 lg:col-span-5 border-b lg:border-b-0 lg:border-r border-[#F9F9F9]/10 pb-12 lg:pb-0 lg:pr-12">
            <h2 class="label-tiny opacity-60 mb-10 font-bold">Status Pesanan</h2>

            @php
                $phases = [
                    [
                        'idx'   => 0,
                        'num'   => '01',
                        'label' => 'Order Diterima',
                        'desc_done'    => $order->created_at->timezone('Asia/Jakarta')->format('d M Y — H:i'),
                        'desc_active'  => 'Pesanan baru saja dibuat. Menunggu pembayaran.',
                        'desc_pending' => 'Order belum diterima.',
                        'icon'  => 'receipt_long',
                    ],
                    [
                        'idx'   => 1,
                        'num'   => '02',
                        'label' => 'Pembayaran Terverifikasi',
                        'desc_done'    => 'Lunas via ' . $order->payment_method,
                        'desc_active'  => 'Pembayaran baru saja dikonfirmasi. Kopi masuk antrian roasting.',
                        'desc_pending' => 'Menunggu konfirmasi pembayaran.',
                        'icon'  => 'verified',
                    ],
                    [
                        'idx'   => 2,
                        'num'   => '03',
                        'label' => 'Roasting & Pengemasan',
                        'desc_done'    => 'Roasting dan pengemasan selesai.',
                        'desc_active'  => 'Kopi sedang di-roast dengan profil khusus di Tuban Roastery. Est. 24 jam.',
                        'desc_pending' => 'Menunggu proses roasting & pengemasan.',
                        'icon'  => 'local_fire_department',
                    ],
                    [
                        'idx'   => 3,
                        'num'   => '04',
                        'label' => 'Dalam Pengiriman',
                        'desc_done'    => 'Paket telah diserahkan ke kurir.',
                        'desc_active'  => 'Paket sedang dalam perjalanan menuju alamat Anda.',
                        'desc_pending' => 'Paket belum dikirim.',
                        'icon'  => 'local_shipping',
                    ],
                    [
                        'idx'   => 4,
                        'num'   => '05',
                        'label' => 'Pesanan Tiba',
                        'desc_done'    => 'Paket telah diterima. Terima kasih! ☕',
                        'desc_active'  => 'Paket telah tiba di tangan Anda!',
                        'desc_pending' => 'Belum tiba.',
                        'icon'  => 'home',
                    ],
                ];
            @endphp

            <div class="relative">
                @foreach ($phases as $phase)
                    @php
                        $state = $phaseState($phase['idx']);
                        $isLast = $phase['idx'] === count($phases) - 1;

                        // Tentukan deskripsi
                        $desc = match($state) {
                            'done'   => $phase['desc_done'],
                            'active' => $phase['desc_active'],
                            default  => $phase['desc_pending'],
                        };
                    @endphp

                    <div class="flex items-start">
                        {{-- Dot + Connector --}}
                        <div class="flex flex-col items-center mr-5" style="width:10px;">
                            <div class="phase-dot {{ $state }}"></div>
                            @if (!$isLast)
                                <div class="phase-connector {{ $state }}" style="height:80px; margin-top:4px;"></div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="pb-8 flex-grow {{ $isLast ? 'pb-0' : '' }}">
                            {{-- Current badge --}}
                            @if ($state === 'active')
                                <span class="current-badge inline-flex items-center gap-1 bg-green-950/70 text-green-400 border border-green-800/60 px-2 py-0.5 mb-2 label-tiny text-[9px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block animate-pulse"></span>
                                    FASE SAAT INI
                                </span>
                            @endif

                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-[14px] {{ $state === 'done' ? 'text-[#F9F9F9]/60' : ($state === 'active' ? 'text-green-400' : 'text-[#F9F9F9]/20') }}">
                                    {{ $state === 'done' ? 'check_circle' : $phase['icon'] }}
                                </span>
                                <p class="label-tiny font-bold {{ $state === 'done' ? 'text-[#F9F9F9]/70' : ($state === 'active' ? 'text-[#F9F9F9]' : 'text-[#F9F9F9]/25') }}">
                                    {{ $phase['num'] }}. {{ $phase['label'] }}
                                </p>
                            </div>
                            <p class="font-sans text-xs leading-relaxed ml-5 {{ $state === 'active' ? 'text-neutral-300' : ($state === 'done' ? 'text-neutral-500' : 'text-neutral-600') }}">
                                {{ $desc }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Progress bar --}}
            <div class="mt-8 pt-8 border-t border-[#F9F9F9]/10">
                <div class="flex justify-between label-tiny text-[9px] opacity-40 mb-2">
                    <span>Progress</span>
                    <span>{{ round(($currentIdx / 4) * 100) }}%</span>
                </div>
                <div class="w-full bg-[#F9F9F9]/10 h-0.5">
                    <div class="h-0.5 bg-[#F9F9F9] transition-all duration-700"
                         style="width: {{ round(($currentIdx / 4) * 100) }}%"></div>
                </div>
                <p class="label-tiny text-[9px] opacity-30 mt-2">
                    Fase {{ $currentIdx + 1 }} dari 5
                </p>
            </div>
        </div>

        {{-- RIGHT: Order Detail ─────────────────────────────────── --}}
        <div class="col-span-12 lg:col-span-7 lg:pl-12 space-y-10">

            {{-- Items --}}
            <div class="space-y-6">
                <h2 class="label-tiny opacity-60 font-bold border-b border-[#F9F9F9]/10 pb-3">Produk Dipesan</h2>
                
                @if (session('review_success'))
                    <div class="mb-4 bg-green-950/40 border border-green-800/60 text-green-300 p-4 text-xs font-bold uppercase tracking-wider">
                        {{ session('review_success') }}
                    </div>
                @endif

                @foreach ($order->items as $item)
                <div class="border-b border-[#F9F9F9]/10 pb-6 last:border-b-0 last:pb-0">
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-20 bg-[#1a1a1a] border border-[#F9F9F9]/10 overflow-hidden flex-shrink-0">
                            @if ($item->product && ($item->product->image_path ?? $item->product->image) && file_exists(public_path('storage/' . ($item->product->image_path ?? $item->product->image))))
                                <img class="w-full h-full object-cover grayscale brightness-90"
                                     src="{{ asset('storage/' . ($item->product->image_path ?? $item->product->image)) }}"
                                     alt="{{ $item->product_name }}">
                            @elseif ($item->product && filter_var($item->product->image_path ?? $item->product->image, FILTER_VALIDATE_URL))
                                <img class="w-full h-full object-cover grayscale brightness-90"
                                     src="{{ $item->product->image_path ?? $item->product->image }}"
                                     alt="{{ $item->product_name }}">
                            @else
                                <img class="w-full h-full object-cover grayscale brightness-90"
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w"
                                     alt="{{ $item->product_name }}">
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <h4 class="font-display text-sm uppercase italic leading-tight">{{ $item->product_name }}</h4>
                                <p class="label-tiny text-[9px] opacity-50 mt-1">{{ $item->grind_size }} | QTY: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-sans text-xs font-semibold mt-2 text-neutral-200">
                                Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if ($order->status === 'Delivered' && $item->product)
                    <div class="mt-4 md:ml-20 bg-[#1a1a1a]/40 p-4 border border-[#F9F9F9]/5">
                        <span class="label-tiny text-[9px] text-green-400 font-bold block mb-2">Tulis Ulasan untuk {{ $item->product_name }}</span>
                        <form action="{{ route('product.review.store', $item->product_id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="customer_name" value="{{ $order->first_name }} {{ $order->last_name }}">
                            
                            <div class="flex items-center gap-3">
                                <label class="label-tiny text-[8px] opacity-60 font-bold">Rating:</label>
                                <div class="flex gap-1.5 rating-stars" data-item-id="{{ $item->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" class="star-btn text-[#F9F9F9]/20 hover:text-yellow-400 transition-colors" data-value="{{ $i }}" onclick="setRating({{ $item->id }}, {{ $i }})">
                                            <span class="material-symbols-outlined text-[16px]">star</span>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input-{{ $item->id }}" value="5">
                            </div>

                            <div class="flex gap-2">
                                <input type="text" name="comment" class="flex-grow py-2 px-3 outline-none text-xs bg-transparent border border-[#444444]" placeholder="Tulis pendapat Anda tentang kopi ini...">
                                <button type="submit" class="bg-[#F9F9F9] text-[#121212] px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="border-t border-[#F9F9F9]/10 pt-8">
                <div class="grid grid-cols-3 gap-4 font-sans text-sm mb-8">
                    <div>
                        <span class="label-tiny opacity-50 block mb-2 font-bold">Subtotal</span>
                        <span class="font-semibold text-neutral-200">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="label-tiny opacity-50 block mb-2 font-bold">Ongkir</span>
                        <span class="font-semibold text-neutral-200">
                            {{ $order->shipping_cost == 0 ? 'GRATIS' : 'Rp. ' . number_format($order->shipping_cost, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="label-tiny opacity-50 block mb-2 font-bold">Total Dibayar</span>
                        <span class="font-display text-2xl text-[#F9F9F9]">
                            Rp. {{ number_format($order->total_paid, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Address & Payment --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-[#F9F9F9]/10 pt-8 text-sm">
                    <div>
                        <span class="label-tiny opacity-50 block mb-3 font-bold">Alamat Pengiriman</span>
                        <address class="not-italic text-neutral-300 leading-relaxed font-sans text-sm">
                            <strong class="text-[#F9F9F9]">{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                            {{ $order->shipping_address }}<br>
                            {{ $order->city }}{{ $order->postal_code ? ', ' . $order->postal_code : '' }}
                        </address>
                        @if ($order->order_notes)
                        <div class="mt-4 p-4 bg-[#1a1a1a]/30 border border-[#F9F9F9]/5">
                            <span class="label-tiny opacity-50 block mb-1 text-[9px] font-bold">Catatan</span>
                            <p class="font-sans text-neutral-400 text-xs italic">{{ $order->order_notes }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-5">
                        <div>
                            <span class="label-tiny opacity-50 block mb-2 font-bold">Metode Pembayaran</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] opacity-50">
                                    {{ $order->payment_method === 'QRIS' ? 'qr_code_2' : 'account_balance' }}
                                </span>
                                <p class="text-neutral-300 font-sans text-sm">{{ $order->payment_method }}</p>
                            </div>
                        </div>
                        <div>
                            <span class="label-tiny opacity-50 block mb-2 font-bold">Status Pembayaran</span>
                            @if ($order->status === 'Awaiting Payment')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border border-amber-600/50 bg-amber-950/30 text-amber-400 label-tiny text-[9px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                    Belum Dibayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border border-green-600/50 bg-green-950/30 text-green-400 label-tiny text-[9px] font-bold">
                                    <span class="material-symbols-outlined text-[12px]">check</span>
                                    Lunas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA ke halaman pembayaran jika belum bayar --}}
            @if ($order->status === 'Awaiting Payment')
            <div class="border-t border-[#F9F9F9]/10 pt-8">
                <a href="{{ route('order.payment', $order->transaction_id) }}"
                   class="inline-flex items-center gap-3 w-full justify-center bg-[#F9F9F9] text-[#121212] py-5 font-bold text-xs uppercase tracking-widest hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all duration-300 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                    Selesaikan Pembayaran
                </a>
            </div>
            @endif
        </div>

    </section>
</main>
@endsection

@section('scripts')
<script>
    function setRating(itemId, rating) {
        // Set the hidden input value
        const input = document.getElementById('rating-input-' + itemId);
        if (input) {
            input.value = rating;
        }
        
        // Update visual stars
        const starContainer = document.querySelector(`.rating-stars[data-item-id="${itemId}"]`);
        if (starContainer) {
            const buttons = starContainer.querySelectorAll('.star-btn');
            buttons.forEach(button => {
                const val = parseInt(button.getAttribute('data-value'));
                const icon = button.querySelector('.material-symbols-outlined');
                if (val <= rating) {
                    button.classList.remove('text-[#F9F9F9]/20');
                    button.classList.add('text-yellow-400');
                    if (icon) {
                        icon.style.fontVariationSettings = "'FILL' 1, 'wght' 200, 'GRAD' 0, 'opsz' 24";
                    }
                } else {
                    button.classList.remove('text-yellow-400');
                    button.classList.add('text-[#F9F9F9]/20');
                    if (icon) {
                        icon.style.fontVariationSettings = "'FILL' 0, 'wght' 200, 'GRAD' 0, 'opsz' 24";
                    }
                }
            });
        }
    }

    // Default set 5 stars visual
    document.addEventListener('DOMContentLoaded', () => {
        const ratingContainers = document.querySelectorAll('.rating-stars');
        ratingContainers.forEach(container => {
            const itemId = container.getAttribute('data-item-id');
            setRating(itemId, 5); // Default to 5 stars
        });
    });
</script>
@endsection
