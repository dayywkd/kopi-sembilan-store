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
    .phase-connector.done      { background: #121212; }
    .phase-connector.active    { background: linear-gradient(to bottom, #121212, rgba(18,18,18,0.05)); }
    .phase-connector.pending   { background: rgba(18,18,18,0.08); }

    /* Phase dot */
    .phase-dot {
        width: 10px;
        height: 10px;
        flex-shrink: 0;
    }
    .phase-dot.done    { background: #121212; }
    .phase-dot.active  { background: #121212; animation: dot-pulse 1.8s ease-in-out infinite; }
    .phase-dot.pending { background: transparent; border: 1px solid #E5E7EB; }

    @keyframes dot-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(18, 18, 18, 0.5); }
        50%       { box-shadow: 0 0 0 6px rgba(18, 18, 18, 0); }
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

    $isPickupOrder = ($order->courier === 'pickup') || (strpos(strtolower($order->shipping_address), 'ambil di toko') !== false);
@endphp

<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto bg-white">

    @if (session('confirm_success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-800 p-6 text-xs uppercase tracking-widest font-bold">
            {{ session('confirm_success') }}
        </div>
    @endif

    {{-- ── Header dinamis berdasarkan status ──────────────────── --}}
    <section class="mb-stack-lg border-b border-[#E5E7EB] pb-8">
        <div class="grid grid-cols-12 items-end gap-4">
            <div class="col-span-12 md:col-span-8">
                @php
                    $headerLabel = match($order->status) {
                        'Awaiting Payment' => 'Menunggu Pembayaran',
                        'Paid'             => 'Pembayaran Diterima',
                        'Packing'          => $isPickupOrder ? 'Sedang Dipersiapkan' : 'Sedang Dikemas',
                        'Shipped'          => $isPickupOrder ? 'Siap Diambil' : 'Dalam Pengiriman',
                        'Delivered'        => $isPickupOrder ? 'Pesanan Telah Diambil' : 'Pesanan Tiba',
                        default            => 'Order Confirmed',
                    };
                    $headerTitle = match($order->status) {
                        'Awaiting Payment' => 'Awaiting Payment',
                        'Paid'             => 'Paid — In Roast',
                        'Packing'          => 'Packing & Prep',
                        'Shipped'          => $isPickupOrder ? 'Ready For Pickup' : 'On The Way',
                        'Delivered'        => $isPickupOrder ? 'Picked Up' : 'Delivered',
                        default            => 'Order Confirmed',
                    };
                    $headerColor = match($order->status) {
                        'Awaiting Payment' => 'text-amber-600',
                        'Paid'             => 'text-blue-600',
                        'Packing'          => 'text-yellow-600',
                        'Shipped'          => 'text-green-600',
                        'Delivered'        => 'text-emerald-600',
                        default            => 'text-neutral-500',
                    };
                @endphp
                <span class="label-tiny {{ $headerColor }} font-bold tracking-widest block mb-2">
                    ● &nbsp;{{ $headerLabel }} — #{{ $order->transaction_id }}
                </span>
                <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none text-[#121212]">
                    {{ $headerTitle }}
                </h1>
            </div>
            <div class="col-span-12 md:col-span-4 md:text-right">
                <p class="font-sans text-neutral-500 text-sm max-w-xs md:ml-auto leading-relaxed">
                    @if ($order->status === 'Awaiting Payment')
                        Segera selesaikan pembayaran agar pesanan Anda diproses.
                    @elseif ($order->status === 'Paid')
                        Pembayaran terverifikasi. Kopi Anda sedang masuk jadwal roasting.
                    @elseif ($order->status === 'Packing')
                        {{ $isPickupOrder ? 'Biji kopi sedang di-roast dan dipersiapkan untuk Anda ambil di roastery kami.' : 'Biji kopi sedang di-roast dan dikemas dengan presisi di Tuban Roastery.' }}
                    @elseif ($order->status === 'Shipped')
                        {{ $isPickupOrder ? 'Pesanan Anda telah siap di roastery Tuban. Silakan ambil langsung di toko kami.' : 'Paket Anda telah diserahkan ke kurir dan sedang dalam perjalanan.' }}
                    @else
                        {{ $isPickupOrder ? 'Pesanan telah diambil langsung di toko. Terima kasih dan selamat menikmati!' : 'Paket telah tiba. Nikmati kopi terbaik Anda!' }}
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- ── Banner Belum Bayar / Dalam Perjalanan / Selesai ── --}}
    @if ($order->status === 'Awaiting Payment')
    <section class="mb-10">
        <div class="bg-brand-cream border border-brand-accent/20 p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-brand-accent text-[28px] flex-shrink-0">pending_actions</span>
                <div>
                    <p class="label-tiny text-brand-accent mb-1">Pembayaran Diperlukan</p>
                    <p class="font-sans text-sm text-brand-accent leading-relaxed">
                        Pesanan belum dibayar. Lakukan pembayaran via <strong>{{ $order->payment_method }}</strong> agar pesanan segera diproses.
                    </p>
                </div>
            </div>
            <a href="{{ route('order.payment', $order->uuid) }}"
               class="flex-shrink-0 flex items-center gap-2 bg-[#121212] text-white hover:bg-brand-accent hover:text-[#FFFFFF] font-bold text-xs uppercase tracking-widest px-6 py-4 transition-all duration-300 active:scale-[0.98] whitespace-nowrap">
                <span class="material-symbols-outlined text-[18px]">payments</span>
                Bayar Sekarang
            </a>
        </div>
    </section>
    @elseif ($order->status === 'Shipped')
    <section class="mb-10">
        <div class="bg-brand-cream border border-brand-accent/20 p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <span class="material-symbols-outlined text-brand-accent text-[28px] flex-shrink-0">
                    {{ $isPickupOrder ? 'store' : 'local_shipping' }}
                </span>
                <div>
                    <p class="label-tiny text-brand-accent mb-1">
                        {{ $isPickupOrder ? 'Pesanan Siap Diambil' : 'Paket Dalam Perjalanan' }}
                    </p>
                    <p class="font-sans text-sm text-brand-accent leading-relaxed">
                        @if ($isPickupOrder)
                            Pesanan Anda telah siap di roastery Tuban. Silakan ambil langsung di toko kami.
                        @else
                            Pesanan Anda sedang dikirim. Nomor Resi: <strong>{{ $order->tracking_number ?? '-' }}</strong>. Silakan konfirmasi jika barang sudah sampai.
                        @endif
                    </p>
                </div>
            </div>
            <form action="{{ route('order.confirm_delivery', $order->uuid) }}" method="POST" class="flex-shrink-0">
                @csrf
                <button type="submit"
                        class="flex items-center gap-2 bg-brand-dark hover:bg-brand-accent text-white font-bold text-xs uppercase tracking-widest px-6 py-4 transition-colors duration-300 active:scale-[0.98] whitespace-nowrap cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">done_all</span>
                    {{ $isPickupOrder ? 'Konfirmasi Pesanan Telah Diambil' : 'Konfirmasi Pesanan Tiba' }}
                </button>
            </form>
        </div>
    </section>
    @elseif ($order->status === 'Delivered')
    <section class="mb-10">
        <div class="bg-brand-cream border border-brand-accent/20 p-6 flex items-center gap-5">
            <span class="material-symbols-outlined text-brand-accent text-[32px]">
                {{ $isPickupOrder ? 'storefront' : 'verified' }}
            </span>
            <div>
                <p class="label-tiny text-brand-accent mb-1">Pesanan Selesai</p>
                <p class="font-sans text-sm text-brand-accent leading-relaxed">
                    @if ($isPickupOrder)
                        Pesanan telah diambil langsung di toko. Terima kasih telah mempercayai <strong>Toko Kopi Sembilan</strong>. Selamat menikmati!
                    @else
                        Paket telah tiba di tangan Anda. Terima kasih telah mempercayai <strong>Toko Kopi Sembilan</strong>. Selamat menikmati!
                    @endif
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- ── Main Grid ────────────────────────────────────────────── --}}
    <section class="grid grid-cols-12 gap-gutter">

        {{-- LEFT: Timeline Status ──────────────────────────────── --}}
        <div class="col-span-12 lg:col-span-5 border-b lg:border-b-0 lg:border-r border-[#E5E7EB] pb-12 lg:pb-0 lg:pr-12">
            <h2 class="label-tiny text-neutral-500 mb-10 font-bold">Status Pesanan</h2>

            @php
                $phase4Label = $isPickupOrder ? 'Siap Diambil' : 'Dalam Pengiriman';
                $phase4DescDone = $isPickupOrder ? 'Pesanan siap diambil di toko.' : 'Paket telah diserahkan ke kurir.';
                $phase4DescActive = $isPickupOrder ? 'Pesanan siap diambil di toko. Silakan kunjungi Roastery kami.' : 'Paket sedang dalam perjalanan menuju alamat Anda.';
                $phase4DescPending = $isPickupOrder ? 'Pesanan belum siap diambil.' : 'Paket belum dikirim.';
                $phase4Icon = $isPickupOrder ? 'store' : 'local_shipping';

                $phase5Label = $isPickupOrder ? 'Pesanan Diambil' : 'Pesanan Tiba';
                $phase5DescDone = $isPickupOrder ? 'Pesanan telah diambil oleh pelanggan. Terima kasih! ☕' : 'Paket telah diterima. Terima kasih! ☕';
                $phase5DescActive = $isPickupOrder ? 'Pesanan telah diambil oleh pelanggan!' : 'Paket telah tiba di tangan Anda!';
                $phase5DescPending = $isPickupOrder ? 'Belum diambil.' : 'Belum tiba.';
                $phase5Icon = $isPickupOrder ? 'check_circle' : 'home';

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
                        'label' => $isPickupOrder ? 'Roasting & Persiapan' : 'Roasting & Pengemasan',
                        'desc_done'    => $isPickupOrder ? 'Roasting dan persiapan selesai.' : 'Roasting dan pengemasan selesai.',
                        'desc_active'  => 'Kopi sedang di-roast dengan profil khusus di Tuban Roastery. Est. 24 jam.',
                        'desc_pending' => $isPickupOrder ? 'Menunggu proses roasting & persiapan.' : 'Menunggu proses roasting & pengemasan.',
                        'icon'  => 'local_fire_department',
                    ],
                    [
                        'idx'   => 3,
                        'num'   => '04',
                        'label' => $phase4Label,
                        'desc_done'    => $phase4DescDone,
                        'desc_active'  => $phase4DescActive,
                        'desc_pending' => $phase4DescPending,
                        'icon'  => $phase4Icon,
                    ],
                    [
                        'idx'   => 4,
                        'num'   => '05',
                        'label' => $phase5Label,
                        'desc_done'    => $phase5DescDone,
                        'desc_active'  => $phase5DescActive,
                        'desc_pending' => $phase5DescPending,
                        'icon'  => $phase5Icon,
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
                                <span class="current-badge inline-flex items-center gap-1 bg-brand-cream text-brand-accent border border-brand-accent/20 px-2 py-0.5 mb-2 label-tiny text-[9px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-accent inline-block animate-pulse"></span>
                                    FASE SAAT INI
                                </span>
                            @endif

                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-[14px] {{ $state === 'done' ? 'text-brand-accent' : ($state === 'active' ? 'text-brand-accent' : 'text-neutral-300') }}">
                                    {{ $state === 'done' ? 'check_circle' : $phase['icon'] }}
                                </span>
                                <p class="label-tiny font-bold {{ $state === 'done' ? 'text-neutral-600' : ($state === 'active' ? 'text-[#121212]' : 'text-neutral-300') }}">
                                    {{ $phase['num'] }}. {{ $phase['label'] }}
                                </p>
                            </div>
                            <p class="font-sans text-xs leading-relaxed ml-5 {{ $state === 'active' ? 'text-neutral-700' : ($state === 'done' ? 'text-neutral-500' : 'text-neutral-400') }}">
                                {{ $desc }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Progress bar --}}
            <div class="mt-8 pt-8 border-t border-[#E5E7EB]">
                <div class="flex justify-between label-tiny text-[9px] text-neutral-400 mb-2">
                    <span>Progress</span>
                    <span>{{ round(($currentIdx / 4) * 100) }}%</span>
                </div>
                <div class="w-full bg-neutral-100 h-0.5">
                    <div class="h-0.5 bg-[#121212] transition-all duration-700"
                         style="width: {{ round(($currentIdx / 4) * 100) }}%"></div>
                </div>
                <p class="label-tiny text-[9px] text-neutral-400 mt-2">
                    Fase {{ $currentIdx + 1 }} dari 5
                </p>
            </div>

            @if (!$isPickupOrder && $order->tracking_events)
                <div class="mt-10 pt-8 border-t border-[#E5E7EB]">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <h2 class="label-tiny text-neutral-500 font-bold">Tracking Resi Real-Time</h2>
                        <span class="text-[9px] text-neutral-400 uppercase tracking-widest">
                            Sync: {{ optional($order->tracking_synced_at)->timezone('Asia/Jakarta')->format('d M H:i') }} WIB
                        </span>
                    </div>
                    <div class="space-y-4">
                        @foreach (array_reverse($order->tracking_events) as $event)
                            <div class="border border-[#E5E7EB] bg-brand-cream p-4">
                                <p class="label-tiny text-[9px] text-neutral-400 mb-1">
                                    {{ !empty($event['datetime']) ? \Illuminate\Support\Carbon::parse($event['datetime'])->timezone('Asia/Jakarta')->format('d M Y H:i') . ' WIB' : 'Update Kurir' }}
                                </p>
                                <p class="font-sans text-sm font-semibold text-[#121212]">{{ $event['status'] ?? 'Update pengiriman' }}</p>
                                @if (!empty($event['note']))
                                    <p class="font-sans text-xs text-neutral-500 mt-1">{{ $event['note'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT: Order Detail ─────────────────────────────────── --}}
        <div class="col-span-12 lg:col-span-7 lg:pl-12 space-y-10">

            {{-- Items --}}
            <div class="space-y-6">
                <h2 class="label-tiny text-neutral-500 font-bold border-b border-[#E5E7EB] pb-3">Produk Dipesan</h2>
                
                @if (session('review_success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 p-4 text-xs font-bold uppercase tracking-wider">
                        {{ session('review_success') }}
                    </div>
                @endif

                @foreach ($order->items as $item)
                <div class="border-b border-[#E5E7EB] pb-6 last:border-b-0 last:pb-0">
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-20 bg-neutral-50 border border-[#E5E7EB] overflow-hidden flex-shrink-0">
                            @if ($item->product && ($item->product->image_path ?? $item->product->image) && file_exists(public_path('storage/' . ($item->product->image_path ?? $item->product->image))))
                                <img class="w-full h-full object-cover"
                                     src="{{ asset('storage/' . ($item->product->image_path ?? $item->product->image)) }}"
                                     alt="{{ $item->product_name }}">
                            @elseif ($item->product && filter_var($item->product->image_path ?? $item->product->image, FILTER_VALIDATE_URL))
                                <img class="w-full h-full object-cover"
                                     src="{{ $item->product->image_path ?? $item->product->image }}"
                                     alt="{{ $item->product_name }}">
                            @else
                                <img class="w-full h-full object-cover"
                                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w"
                                     alt="{{ $item->product_name }}">
                            @endif
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <h4 class="font-display text-sm uppercase italic leading-tight text-[#121212]">{{ $item->product_name }}</h4>
                                <p class="label-tiny text-[9px] text-neutral-400 mt-1">{{ $item->grind_size }} | QTY: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-sans text-xs font-semibold mt-2 text-neutral-800">
                                Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if ($order->status === 'Delivered' && $item->product)
                    <div class="mt-4 md:ml-20 bg-brand-cream p-4 border border-[#E5E7EB]">
                        <span class="label-tiny text-[9px] text-brand-accent font-bold block mb-2">Tulis Ulasan untuk {{ $item->product_name }}</span>
                        <form action="{{ route('product.review.store', $item->product_id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="customer_name" value="{{ $order->first_name }} {{ $order->last_name }}">
                            
                            <div class="flex items-center gap-3">
                                <label class="label-tiny text-[8px] text-neutral-400 font-bold">Rating:</label>
                                <div class="flex gap-1.5 rating-stars" data-item-id="{{ $item->id }}">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" class="star-btn text-neutral-300 hover:text-brand-accent transition-colors" data-value="{{ $i }}" onclick="setRating({{ $item->id }}, {{ $i }})">
                                            <span class="material-symbols-outlined text-[16px]">star</span>
                                        </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="rating-input-{{ $item->id }}" value="5">
                            </div>

                            <div class="flex gap-2">
                                <input type="text" name="comment" class="flex-grow py-2 px-3 outline-none text-xs bg-white border border-[#E5E7EB] text-[#121212] focus:border-brand-accent" placeholder="Tulis pendapat Anda tentang kopi ini...">
                                <button type="submit" class="bg-brand-dark text-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-brand-accent hover:border-brand-accent hover:text-white border border-brand-dark transition-all">
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
            <div class="border-t border-[#E5E7EB] pt-8">
                <div class="grid grid-cols-3 gap-4 font-sans text-sm mb-8">
                    <div>
                        <span class="label-tiny text-neutral-400 block mb-2 font-bold">Subtotal</span>
                        <span class="font-semibold text-neutral-800">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="label-tiny text-neutral-400 block mb-2 font-bold">Ongkir</span>
                        <span class="font-semibold text-neutral-800">
                            {{ $order->shipping_cost == 0 ? 'GRATIS' : 'Rp. ' . number_format($order->shipping_cost, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="label-tiny text-neutral-400 block mb-2 font-bold">Total Dibayar</span>
                        <span class="font-display text-base font-bold text-[#121212]">
                            Rp. {{ number_format($order->total_paid, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Address, Courier, & Payment --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-[#E5E7EB] pt-8 text-sm">
                    <div>
                        @if ($isPickupOrder)
                            <span class="label-tiny text-neutral-400 block mb-3 font-bold">Informasi Pengambilan</span>
                            <address class="not-italic text-neutral-600 leading-relaxed font-sans text-sm">
                                <strong class="text-[#121212]">{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 border border-[#121212] bg-[#121212] text-white label-tiny text-[9px] font-bold mb-2">
                                    Ambil di Toko (Local Pickup)
                                </span><br>
                                Silakan ambil pesanan Anda langsung di:<br>
                                <strong>Roastery Toko Kopi Sembilan</strong><br>
                                Tuban, Jawa Timur
                            </address>
                        @else
                            <span class="label-tiny text-neutral-400 block mb-3 font-bold">Alamat Pengiriman</span>
                            <address class="not-italic text-neutral-600 leading-relaxed font-sans text-sm">
                                <strong class="text-[#121212]">{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->city }}{{ $order->postal_code ? ', ' . $order->postal_code : '' }}
                            </address>
                        @endif

                        @php
                            $displayedNotes = $order->order_notes;
                            $displayedNotes = preg_replace('/^\[Kurir:[^\]]+\]\r?\n?/', '', $displayedNotes);
                            $displayedNotes = trim($displayedNotes);
                        @endphp
                        @if ($displayedNotes)
                        <div class="mt-4 p-4 bg-brand-cream border border-[#E5E7EB]">
                            <span class="label-tiny text-neutral-400 block mb-1 text-[9px] font-bold">Catatan</span>
                            <p class="font-sans text-neutral-500 text-xs italic">{{ $displayedNotes }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-5">
                        <div>
                            @if ($isPickupOrder)
                                <span class="label-tiny text-neutral-400 block mb-2 font-bold">Metode Penerimaan</span>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-neutral-400">store</span>
                                    <p class="text-neutral-600 font-sans text-sm font-semibold">Ambil di Toko (Local Pickup)</p>
                                </div>
                            @else
                                <span class="label-tiny text-neutral-400 block mb-2 font-bold">Metode Pengiriman</span>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-neutral-400">local_shipping</span>
                                    <p class="text-neutral-600 font-sans text-sm font-semibold">
                                        @php
                                            $courierCode = $order->courier;
                                            if (!$courierCode && preg_match('/\[Kurir:\s*([^\]\-]+)/', $order->order_notes, $matches)) {
                                                $courierCode = trim($matches[1]);
                                            }
                                            
                                            $serviceName = $order->shipping_service;
                                            if (!$serviceName && preg_match('/\[Kurir:\s*[^\]\-]+\-\s*([^\]]+)/', $order->order_notes, $matches)) {
                                                $serviceName = trim($matches[1]);
                                            }

                                            $courierDisplay = 'Kurir Pengiriman';
                                            if ($courierCode) {
                                                $cClean = strtolower($courierCode);
                                                if ($cClean === 'jne') {
                                                    $courierDisplay = 'JNE Reguler';
                                                } elseif ($cClean === 'jnt' || $cClean === 'j&t') {
                                                    $courierDisplay = 'J&T';
                                                } else {
                                                    $courierDisplay = strtoupper($courierCode);
                                                }
                                            }
                                            if ($serviceName) {
                                                $courierDisplay .= ' (' . strtoupper($serviceName) . ')';
                                            }
                                        @endphp
                                        {{ $courierDisplay }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="label-tiny text-neutral-400 block mb-2 font-bold">Metode Pembayaran</span>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-neutral-400">
                                    {{ $order->payment_method === 'QRIS' ? 'qr_code_2' : 'account_balance' }}
                                </span>
                                <p class="text-neutral-600 font-sans text-sm">{{ $order->payment_method }}</p>
                            </div>
                        </div>
                        <div>
                            <span class="label-tiny text-neutral-400 block mb-2 font-bold">Status Pembayaran</span>
                            @if ($order->status === 'Awaiting Payment')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border border-brand-accent/20 bg-brand-cream text-brand-accent label-tiny text-[9px] font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-accent animate-pulse"></span>
                                    Belum Dibayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 border border-[#A7F3D0] bg-[#E2F9EB] text-[#121212] label-tiny text-[9px] font-bold">
                                    <span class="material-symbols-outlined text-[12px] text-green-700">check</span>
                                    Lunas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA ke halaman pembayaran jika belum bayar --}}
            @if ($order->status === 'Awaiting Payment')
            <div class="border-t border-[#E5E7EB] pt-8">
                <a href="{{ route('order.payment', $order->uuid) }}"
                   class="inline-flex items-center gap-3 w-full justify-center bg-[#121212] text-[#FFFFFF] py-5 font-bold text-xs uppercase tracking-widest hover:bg-brand-accent hover:border-brand-accent hover:text-white border border-[#121212] transition-all duration-300 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                    Selesaikan Pembayaran
                </a>
            </div>
            @endif

            <div class="border-t border-[#E5E7EB] pt-8">
                <a href="{{ route('order.invoice.download', $order->uuid) }}"
                   class="inline-flex items-center gap-3 w-full justify-center border border-[#121212] text-[#121212] py-5 font-bold text-xs uppercase tracking-widest hover:bg-[#121212] hover:text-white transition-all duration-300 active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                    Download Invoice PDF
                </a>
            </div>
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
                    button.classList.remove('text-neutral-300');
                    button.classList.add('text-brand-accent');
                    if (icon) {
                        icon.style.fontVariationSettings = "'FILL' 1, 'wght' 200, 'GRAD' 0, 'opsz' 24";
                    }
                } else {
                    button.classList.remove('text-brand-accent');
                    button.classList.add('text-neutral-300');
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
