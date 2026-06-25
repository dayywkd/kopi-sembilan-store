@extends('layouts.app')

@section('title', 'Pembayaran — ' . $order->transaction_id . ' | Toko Kopi Sembilan')

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

    /* Countdown timer */
    @keyframes countdown-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .countdown-active { animation: countdown-pulse 2s ease-in-out infinite; }

    /* Copy success animation */
    @keyframes copy-flash {
        0% { background: #121212; color: #FFFFFF; }
        100% { background: transparent; color: #121212; }
    }
    .copy-flash { animation: copy-flash 0.4s ease; }

    /* Tab indicator */
    .payment-tab { transition: all 0.3s ease; }
    .payment-tab.active {
        background: #121212;
        color: #FFFFFF;
    }

    /* Step indicator */
    .step-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        font-family: 'DM Mono', monospace;
    }

    /* Shimmer animation for unique code highlight */
    @keyframes shimmer {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    .unique-code-badge {
        background: linear-gradient(90deg, rgba(18,18,18,0.02) 0%, rgba(18,18,18,0.06) 50%, rgba(18,18,18,0.02) 100%);
        background-size: 200% auto;
        animation: shimmer 3s linear infinite;
    }

    /* QRIS card glow */
    .qris-card {
        box-shadow: 0 0 40px rgba(18, 18, 18, 0.02);
        transition: box-shadow 0.3s ease;
    }
    .qris-card:hover {
        box-shadow: 0 0 60px rgba(18, 18, 18, 0.04);
    }

    /* Amount display */
    .amount-display {
        font-feature-settings: "tnum";
        letter-spacing: 0.02em;
    }

    /* Verified badge pulse */
    @keyframes badge-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        50% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
    }
    .badge-glow { animation: badge-glow 2s ease infinite; }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto bg-white">

    {{-- Header --}}
    <section class="mb-12 border-b border-[#E5E7EB] pb-8">
        <div class="grid grid-cols-12 items-end gap-4">
            <div class="col-span-12 md:col-span-8">
                <span class="label-tiny block mb-2 text-neutral-500">
                    <span class="text-amber-500">●</span>&nbsp; Menunggu Pembayaran
                </span>
                <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none text-[#121212]">
                    @if ($order->payment_method === 'QRIS')
                        Scan &amp; Pay
                    @else
                        Transfer &amp; Konfirmasi
                    @endif
                </h1>
            </div>
            <div class="col-span-12 md:col-span-4 md:text-right">
                <p class="label-tiny text-neutral-500 leading-relaxed">
                    Order <span class="text-[#121212] font-bold">{{ $order->transaction_id }}</span>
                </p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-12 gap-gutter">

        {{-- LEFT: Payment Instructions --}}
        <div class="col-span-12 lg:col-span-7 space-y-8">

            {{-- Payment Method Tabs (if Awaiting Payment) --}}
            @if ($order->payment_method === 'Bank Transfer')
                {{-- === BANK TRANSFER SECTION === --}}
                <div class="space-y-6">
                    <div class="border-b border-[#E5E7EB] pb-3">
                        <h2 class="label-tiny text-neutral-500">01 / Instruksi Transfer</h2>
                    </div>

                    {{-- Alert Banner --}}
                    <div class="bg-brand-cream border border-brand-accent/20 p-5 flex gap-4 items-start">
                        <span class="material-symbols-outlined text-brand-accent text-[22px] flex-shrink-0 mt-0.5">info</span>
                        <div>
                            <p class="label-tiny text-brand-accent mb-1">Penting</p>
                            <p class="font-sans text-xs text-brand-accent leading-relaxed">
                                Transfer dengan jumlah tepat sesuai nominal yang tertera, lalu cantumkan kode pesanan Anda di berita transfer.
                            </p>
                        </div>
                    </div>

                    {{-- Rekening Transfer --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Rekening BCA --}}
                        <div class="border border-[#E5E7EB] bg-[#FFFFFF] p-7 space-y-6">
                            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-5">
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">Bank Tujuan</p>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#005BAC] text-white font-black text-xs px-2.5 py-1 font-sans" style="letter-spacing:0.05em">BCA</div>
                                        <span class="font-display text-xl italic text-[#121212]">Bank Central Asia</span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-neutral-300 text-3xl">account_balance</span>
                            </div>

                            <div class="space-y-4">
                                {{-- Nomor Rekening --}}
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">No. Rekening</p>
                                    <div class="flex items-center gap-2">
                                        <p id="rek-bca" class="font-sans text-xl font-bold tracking-wider text-[#121212]">5550305307</p>
                                        <button onclick="copyToClipboard('5550305307', 'btn-copy-rek-bca')" id="btn-copy-rek-bca" class="p-1.5 border border-[#E5E7EB] hover:border-[#121212] transition-colors group" title="Salin nomor rekening">
                                            <span class="material-symbols-outlined text-[14px] text-neutral-400 group-hover:text-[#121212] transition-colors">content_copy</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Atas Nama --}}
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">Atas Nama</p>
                                    <p class="font-sans text-base font-semibold text-[#121212]">Muhammad Fahad</p>
                                </div>
                            </div>
                        </div>

                        {{-- Rekening BRI --}}
                        <div class="border border-[#E5E7EB] bg-[#FFFFFF] p-7 space-y-6">
                            <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-5">
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">Bank Tujuan</p>
                                    <div class="flex items-center gap-3">
                                        <div class="bg-[#00529C] text-white font-black text-xs px-2.5 py-1 font-sans" style="letter-spacing:0.05em">BRI</div>
                                        <span class="font-display text-xl italic text-[#121212]">Bank Rakyat Indonesia</span>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-neutral-300 text-3xl">account_balance</span>
                            </div>

                            <div class="space-y-4">
                                {{-- Nomor Rekening --}}
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">No. Rekening</p>
                                    <div class="flex items-center gap-2">
                                        <p id="rek-bri" class="font-sans text-xl font-bold tracking-wider text-[#121212]">010901031684534</p>
                                        <button onclick="copyToClipboard('010901031684534', 'btn-copy-rek-bri')" id="btn-copy-rek-bri" class="p-1.5 border border-[#E5E7EB] hover:border-[#121212] transition-colors group" title="Salin nomor rekening">
                                            <span class="material-symbols-outlined text-[14px] text-neutral-400 group-hover:text-[#121212] transition-colors">content_copy</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Atas Nama --}}
                                <div>
                                    <p class="label-tiny text-neutral-400 mb-1">Atas Nama</p>
                                    <p class="font-sans text-base font-semibold text-[#121212]">Muhammad Fahad</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Jumlah Transfer --}}
                    <div class="border border-[#E5E7EB] bg-[#FFFFFF] p-7 space-y-6">
                        <div class="bg-brand-cream border border-[#E5E7EB] p-6">
                            <p class="label-tiny text-neutral-500 mb-3">Jumlah Transfer <span class="text-brand-accent font-bold"></span></p>
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <p class="font-sans text-xs text-neutral-500 mb-1">Total yang harus ditransfer</p>
                                    <div class="font-display text-4xl md:text-5xl text-[#121212] amount-display leading-none">
                                        {!! $order->formatted_total_paid !!}
                                    </div>
                                </div>
                                <button onclick="copyToClipboard('{{ $order->total_paid }}', 'btn-copy-amount')" id="btn-copy-amount" class="px-4 py-3 border border-[#E5E7EB] hover:border-brand-accent hover:bg-brand-accent hover:text-[#FFFFFF] text-xs font-sans font-semibold uppercase tracking-widest transition-colors flex items-center gap-2 whitespace-nowrap text-[#121212]">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                    Salin Nominal
                                </button>
                            </div>
                        </div>

                        {{-- Berita / Keterangan Transfer --}}
                        <div>
                            <p class="label-tiny text-neutral-400 mb-3">Berita / Keterangan Transfer</p>
                            <div class="flex items-center gap-3 bg-brand-cream border border-[#E5E7EB] px-5 py-4">
                                <code class="font-mono text-sm text-[#121212] flex-grow">{{ $order->transaction_id }}</code>
                                <button onclick="copyToClipboard('{{ $order->transaction_id }}', 'btn-copy-id')" id="btn-copy-id" class="p-1.5 border border-[#E5E7EB] hover:border-brand-accent transition-colors group flex-shrink-0">
                                    <span class="material-symbols-outlined text-[16px] text-neutral-400 group-hover:text-brand-accent transition-colors">content_copy</span>
                                </button>
                            </div>
                            <p class="font-sans text-[10px] text-neutral-400 mt-2 leading-relaxed">
                                Cantumkan kode pesanan di atas sebagai berita/keterangan transfer agar pesanan Anda dapat diverifikasi secara otomatis.
                            </p>
                        </div>
                    </div>

                    {{-- Steps --}}
                    <div class="border-b border-[#E5E7EB] pb-3 mt-10">
                        <h2 class="label-tiny text-neutral-500">02 / Langkah Pembayaran</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach ([
                            ['Buka aplikasi m-banking atau ATM Bank Anda.', 'phone_android'],
                            ['Pilih menu Transfer → ke Rekening Bank (BCA atau BRI).', 'compare_arrows'],
                            ['Masukkan nomor rekening tujuan (<strong class="text-[#121212]">5550305307</strong> untuk BCA atau <strong class="text-[#121212]">010901031684534</strong> untuk BRI) atas nama <strong class="text-[#121212]">Muhammad Fahad</strong>.', 'dialpad'],
                            ['Masukkan nominal transfer <strong class="text-amber-600">PERSIS TEPAT</strong> sesuai yang tertera.', 'payments'],
                            ['Isi berita transfer dengan kode pesanan: <strong class="font-mono text-[#121212]">' . $order->transaction_id . '</strong>', 'edit_note'],
                            ['Konfirmasi dan selesaikan transfer. Simpan bukti transfer Anda.', 'check_circle'],
                        ] as $i => [$step, $icon])
                        <div class="flex items-start gap-4 py-4 border-b border-[#E5E7EB]/50 last:border-0">
                            <div class="step-num bg-neutral-50 text-neutral-500">{{ $i + 1 }}</div>
                            <div class="flex-grow">
                                <p class="font-sans text-sm text-neutral-700 leading-relaxed">{!! $step !!}</p>
                            </div>
                            <span class="material-symbols-outlined text-neutral-300 text-[20px] flex-shrink-0">{{ $icon }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Konfirmasi via WhatsApp --}}
                    <div class="border-b border-[#E5E7EB] pb-3 mt-10">
                        <h2 class="label-tiny text-neutral-500">03 / Konfirmasi Pembayaran</h2>
                    </div>

                    <div class="bg-brand-cream border border-[#E5E7EB] p-6 space-y-5">
                        <p class="font-sans text-sm text-neutral-600 leading-relaxed">
                            Setelah melakukan transfer, silakan kirimkan bukti pembayaran Anda langsung ke WhatsApp kami untuk konfirmasi instan.
                        </p>
                        <a id="wa-confirm-btn" href="#" target="_blank" rel="noopener noreferrer"
                           class="w-full flex items-center justify-center gap-3 bg-[#25D366] text-white font-semibold py-4 text-sm uppercase tracking-widest hover:bg-[#128C7E] transition-colors duration-300 active:scale-[0.98]">
                            <svg class="fill-current" style="width:20px;height:20px;" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.498 1.452 5.43 1.453 5.414 0 9.82-4.402 9.823-9.817.002-2.624-1.02-5.09-2.88-6.953-1.862-1.863-4.33-2.887-6.954-2.889-5.422 0-9.827 4.404-9.831 9.82-.001 1.943.506 3.841 1.47 5.509l-.965 3.526 3.61-.947zm11.231-6.793c-.302-.152-1.791-.883-2.073-.985-.282-.102-.489-.152-.696.152-.207.304-.799.985-.979 1.187-.18.203-.361.228-.663.077-1.127-.565-1.928-1.01-2.697-2.327-.2-.343.2-.319.574-1.066.06-.122.03-.228-.015-.319-.045-.091-.489-1.18-.671-1.616-.177-.428-.356-.369-.489-.376-.127-.007-.272-.008-.418-.008-.145 0-.382.054-.582.273-.2.22-.763.746-.763 1.82 0 1.073.782 2.107.891 2.254.11.147 1.54 2.349 3.729 3.291.52.224.926.358 1.242.459.522.167 1.002.143 1.379.087.42-.063 1.291-.527 1.472-1.034.18-.506.18-.94.127-1.034-.053-.09-.203-.152-.505-.304z"/>
                            </svg>
                            Kirim Bukti Transfer via WhatsApp
                        </a>
                        <p class="font-sans text-[10px] text-center text-neutral-400 uppercase tracking-wider">
                            Pesanan akan diproses setelah pembayaran terverifikasi oleh tim kami (maks. 1×24 jam)
                        </p>
                    </div>
                </div>

            @elseif ($order->payment_method === 'QRIS')
                {{-- === QRIS SECTION === --}}
                <div class="space-y-6">
                    <div class="border-b border-[#E5E7EB] pb-3">
                        <h2 class="label-tiny text-neutral-500">01 / Scan QRIS</h2>
                    </div>

                    {{-- QRIS Card --}}
                    <div class="qris-card border border-[#E5E7EB] bg-brand-cream p-8 text-center space-y-6">
                        {{-- Total to Pay --}}
                        <div>
                            <p class="label-tiny text-neutral-400 mb-2">Total Pembayaran</p>
                            <div class="font-display text-5xl md:text-6xl text-[#121212] amount-display">
                                {!! $order->formatted_total_paid !!}
                            </div>
                            <p class="font-sans text-xs text-neutral-400 mt-2">Pastikan nominal sesuai sebelum scan</p>
                        </div>

                        {{-- QRIS Image --}}
                        <div class="relative inline-block">
                            <div class="w-56 h-56 md:w-64 md:h-64 mx-auto bg-white p-3 border border-[#E5E7EB] shadow-lg">
                                <img src="{{ asset('images/qris-toko.jpg') }}"
                                     alt="QRIS Toko Kopi Sembilan"
                                     class="w-full h-full object-contain">
                            </div>
                            <div class="absolute -top-2 -right-2 bg-[#25D366] text-white px-2 py-0.5 label-tiny text-[9px] badge-glow">
                                AKTIF
                            </div>
                        </div>

                        {{-- QRIS Provider Note --}}
                        <div class="flex items-center justify-center gap-2 text-neutral-400">
                            <span class="material-symbols-outlined text-[16px]">qr_code_2</span>
                            <p class="label-tiny text-[9px]">QRIS berlaku untuk semua aplikasi dompet digital & mobile banking</p>
                        </div>
                    </div>

                    {{-- Steps QRIS --}}
                    <div class="border-b border-[#E5E7EB] pb-3">
                        <h2 class="label-tiny text-neutral-500">02 / Cara Pembayaran QRIS</h2>
                    </div>
                    <div class="space-y-4">
                        @foreach ([
                            ['Buka aplikasi pembayaran Anda (GoPay, OVO, Dana, ShopeePay, m-banking, dll.)', 'phone_android'],
                            ['Pilih menu <strong class="text-[#121212]">Scan QR</strong> atau <strong class="text-[#121212]">QRIS</strong>.', 'qr_code_scanner'],
                            ['Arahkan kamera ke QR code di atas.', 'photo_camera'],
                            ['Pastikan nominal pembayaran sesuai: <strong class="font-mono text-amber-600 font-bold">Rp. ' . number_format($order->total_paid, 0, ',', '.') . '</strong>', 'payments'],
                            ['Konfirmasi pembayaran dan selesai!', 'check_circle'],
                        ] as $i => [$step, $icon])
                        <div class="flex items-start gap-4 py-4 border-b border-[#E5E7EB]/50 last:border-0">
                            <div class="step-num bg-neutral-50 text-neutral-500">{{ $i + 1 }}</div>
                            <p class="font-sans text-sm text-neutral-700 leading-relaxed flex-grow">{!! $step !!}</p>
                            <span class="material-symbols-outlined text-neutral-300 text-[20px] flex-shrink-0">{{ $icon }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Konfirmasi via WhatsApp --}}
                    <div class="border-b border-[#E5E7EB] pb-3 mt-6">
                        <h2 class="label-tiny text-neutral-500">03 / Konfirmasi Pembayaran</h2>
                    </div>
                    <div class="bg-brand-cream border border-[#E5E7EB] p-6 space-y-5">
                        <p class="font-sans text-sm text-neutral-600 leading-relaxed">
                            Setelah melakukan scan, silakan kirimkan bukti pembayaran QRIS Anda langsung ke WhatsApp kami untuk konfirmasi instan.
                        </p>
                        <a id="wa-confirm-btn" href="#" target="_blank" rel="noopener noreferrer"
                           class="w-full flex items-center justify-center gap-3 bg-[#25D366] text-white font-semibold py-4 text-sm uppercase tracking-widest hover:bg-[#128C7E] transition-colors duration-300 active:scale-[0.98]">
                            <svg class="fill-current" style="width:20px;height:20px;" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.498 1.452 5.43 1.453 5.414 0 9.82-4.402 9.823-9.817.002-2.624-1.02-5.09-2.88-6.953-1.862-1.863-4.33-2.887-6.954-2.889-5.422 0-9.827 4.404-9.831 9.82-.001 1.943.506 3.841 1.47 5.509l-.965 3.526 3.61-.947zm11.231-6.793c-.302-.152-1.791-.883-2.073-.985-.282-.102-.489-.152-.696.152-.207.304-.799.985-.979 1.187-.18.203-.361.228-.663.077-1.127-.565-1.928-1.01-2.697-2.327-.2-.343.2-.319.574-1.066.06-.122.03-.228-.015-.319-.045-.091-.489-1.18-.671-1.616-.177-.428-.356-.369-.489-.376-.127-.007-.272-.008-.418-.008-.145 0-.382.054-.582.273-.2.22-.763.746-.763 1.82 0 1.073.782 2.107.891 2.254.11.147 1.54 2.349 3.729 3.291.52.224.926.358 1.242.459.522.167 1.002.143 1.379.087.42-.063 1.291-.527 1.472-1.034.18-.506.18-.94.127-1.034-.053-.09-.203-.152-.505-.304z"/>
                            </svg>
                            Kirim Bukti QRIS via WhatsApp
                        </a>
                        <p class="font-sans text-[10px] text-center text-neutral-400 uppercase tracking-wider">
                            Tim kami akan memverifikasi dan memulai proses roasting dalam 1×24 jam
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT: Order Summary Sticky --}}
        <div class="col-span-12 lg:col-span-5">
            <div class="lg:sticky lg:top-28 space-y-6">

                {{-- Order Summary Card --}}
                <div class="border border-[#E5E7EB] bg-brand-cream p-8 space-y-8">
                    <h3 class="label-tiny text-neutral-500 border-b border-[#E5E7EB] pb-4">Ringkasan Pesanan</h3>

                    {{-- Items --}}
                    <div class="space-y-5">
                        @foreach ($order->items as $item)
                        <div class="flex gap-4 items-center">
                            <div class="w-14 h-18 bg-brand-cream border border-[#E5E7EB] overflow-hidden flex-shrink-0" style="height:72px;width:56px;">
                                @if ($item->product)
                                    <img class="w-full h-full object-cover"
                                         src="{{ $item->product->image_url }}"
                                         alt="{{ $item->product_name }}">
                                @else
                                    <img class="w-full h-full object-cover"
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w"
                                         alt="{{ $item->product_name }}">
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-display text-sm uppercase italic leading-tight truncate text-[#121212]">{{ $item->product_name }}</h4>
                                <p class="label-tiny text-[9px] text-neutral-400 mt-1">{{ $item->grind_size }} | QTY: {{ $item->quantity }}</p>
                                <span class="font-sans text-xs font-semibold mt-1 block text-neutral-800">
                                    Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-[#E5E7EB] pt-6 space-y-3 font-sans text-sm text-neutral-700">
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Subtotal</span>
                            <span class="font-semibold text-neutral-800">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Ongkos Kirim</span>
                            <span class="font-semibold text-neutral-800">
                                {{ $order->shipping_cost == 0 ? 'GRATIS' : 'Rp. ' . number_format($order->shipping_cost, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between pt-4 border-t border-[#E5E7EB]">
                            <span class="label-tiny font-bold text-neutral-800">Total Bayar</span>
                            <div class="text-right">
                                <div class="font-display text-2xl leading-none text-[#121212]">{!! $order->formatted_total_paid !!}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="border border-[#E5E7EB] bg-brand-cream p-6 space-y-4">
                    @if (str_contains(strtolower($order->shipping_address), 'ambil di toko'))
                        <h3 class="label-tiny text-neutral-500">Info Penerimaan (Local Pickup)</h3>
                        <div class="font-sans text-sm space-y-2 text-neutral-600">
                            <p class="font-semibold text-[#121212]">{{ $order->first_name }} {{ $order->last_name }}</p>
                            <p>{{ $order->email }}</p>
                            <p class="text-xs leading-relaxed mt-2 text-neutral-500">
                                <strong>Metode:</strong> Ambil Sendiri di Toko<br>
                                <strong>Lokasi:</strong> Roastery Toko Kopi Sembilan (Kutorejo, Tuban)<br>
                                <span class="text-neutral-400">Silakan ambil pesanan Anda setelah status berubah menjadi "Packing" (siap diambil).</span>
                            </p>
                        </div>
                    @else
                        <h3 class="label-tiny text-neutral-500">Info Pengiriman</h3>
                        <div class="font-sans text-sm space-y-2 text-neutral-600">
                            <p class="font-semibold text-[#121212]">{{ $order->first_name }} {{ $order->last_name }}</p>
                            <p>{{ $order->email }}</p>
                            <p class="text-xs leading-relaxed mt-2 text-neutral-500">
                                {{ $order->shipping_address }}<br>
                                {{ $order->city }}{{ $order->postal_code ? ', ' . $order->postal_code : '' }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- CTA to Tracking Page --}}
                <a href="{{ route('order.tracking', $order->uuid) }}"
                   class="w-full flex items-center justify-center gap-2 border border-[#121212] py-4 text-[#121212] hover:bg-brand-accent hover:border-brand-accent hover:text-[#FFFFFF] transition-all label-tiny text-[10px] font-bold">
                    <span class="material-symbols-outlined text-[16px]">track_changes</span>
                    Lihat Status Pesanan
                </a>
                <a href="{{ route('order.invoice.download', $order->uuid) }}"
                   class="w-full flex items-center justify-center gap-2 border border-[#E5E7EB] bg-white py-4 text-[#121212] hover:bg-brand-cream transition-all label-tiny text-[10px] font-bold">
                    <span class="material-symbols-outlined text-[16px]">receipt</span>
                    Unduh Foto Struk
                </a>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // WhatsApp confirmation link builder
    function buildWALink() {
        const txId = '{{ $order->transaction_id }}';
        const name = '{{ $order->first_name }} {{ $order->last_name }}';
        const total = '{{ number_format($order->total_paid, 0, ",", ".") }}';
        const method = '{{ $order->payment_method }}';
        const phoneOwner = '6285855180131';

        let message = '';
        if (method === 'Bank Transfer') {
            message =
                `Halo Toko Kopi Sembilan,\n\n` +
                `Saya ingin melakukan konfirmasi pembayaran untuk pesanan berikut:\n\n` +
                `- Nomor Pesanan: *${txId}*\n` +
                `- Nama Lengkap: ${name}\n` +
                `- Metode Pembayaran: Bank Transfer BCA/BRI\n` +
                `- Nominal Transfer: Rp. ${total}\n\n` +
                `[Bukti transfer telah saya lampirkan]\n\n` +
                `Mohon bantuannya untuk segera diverifikasi. Terima kasih.`;
        } else {
            message =
                `Halo Toko Kopi Sembilan,\n\n` +
                `Saya ingin melakukan konfirmasi pembayaran QRIS untuk pesanan berikut:\n\n` +
                `- Nomor Pesanan: *${txId}*\n` +
                `- Nama Lengkap: ${name}\n` +
                `- Metode Pembayaran: QRIS\n` +
                `- Nominal Pembayaran: Rp. ${total}\n\n` +
                `[Bukti pembayaran QRIS telah saya lampirkan]\n\n` +
                `Mohon bantuannya untuk segera diproses. Terima kasih.`;
        }

        const waBtn = document.getElementById('wa-confirm-btn');
        if (waBtn) {
            waBtn.href = `https://wa.me/${phoneOwner}?text=${encodeURIComponent(message)}`;
        }
    }

    // Copy to clipboard function
    function copyToClipboard(text, btnId) {
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById(btnId);
            if (!btn) return;

            const originalHtml = btn.innerHTML;
            btn.innerHTML = `<span class="material-symbols-outlined text-[16px] text-green-600">check</span>`;
            btn.classList.add('border-green-600');

            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('border-green-600');
            }, 2000);
        }).catch(() => {
            // Fallback for older browsers
            const el = document.createElement('textarea');
            el.value = text;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
        });
    }

    // Initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        buildWALink();
    });
</script>
@endsectioncument.body.removeChild(el);
        });
    }

    // Initialize on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        buildWALink();
    });
</script>
@endsection
