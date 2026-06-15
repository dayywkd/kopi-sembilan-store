@extends('layouts.app')

@section('title', 'Order Tracking - ' . $order->transaction_id . ' | Toko Kopi Sembilan')

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
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
    <!-- Header -->
    <section class="mb-stack-lg border-b border-[#F9F9F9]/15 pb-8">
        <div class="grid grid-cols-12 items-baseline">
            <div class="col-span-12 md:col-span-8">
                <span class="label-tiny opacity-60 text-green-500 font-bold tracking-widest block mb-2">#{{ $order->transaction_id }}</span>
                <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none">Order Confirmed</h1>
            </div>
            <div class="col-span-12 md:col-span-4 md:text-right mt-6 md:mt-0">
                <p class="font-sans text-neutral-300 text-sm max-w-xs md:ml-auto leading-relaxed">
                    Your specialty beans are currently scheduled for roasting. We roast with absolute precision.
                </p>
            </div>
        </div>
    </section>

    <!-- Tracking & Details -->
    <section class="grid grid-cols-12 gap-gutter">
        <!-- Left: Tracking Visual -->
        <div class="col-span-12 lg:col-span-5 border-b lg:border-b-0 lg:border-r border-[#F9F9F9]/10 pb-12 lg:pb-0 lg:pr-12">
            <h2 class="label-tiny opacity-60 mb-8 font-bold">Status Architecture</h2>
            <div class="relative space-y-0">
                
                <!-- Phase 1: Order Placed (Solid White) -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4 h-full">
                        <div class="w-2.5 h-2.5 bg-[#F9F9F9]"></div>
                        <div class="w-[1px] h-24 bg-[#F9F9F9]/30"></div>
                    </div>
                    <div class="pb-8">
                        <p class="label-tiny text-[#F9F9F9] font-bold">01. Order Placed</p>
                        <p id="order-date" class="font-sans text-xs text-neutral-400 mt-1">
                            {{ $order->created_at->timezone('Asia/Jakarta')->format('F d, Y — H:i A') }}
                        </p>
                    </div>
                </div>
                
                <!-- Phase 2: Payment Verified -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4 h-full">
                        <div class="w-2.5 h-2.5 bg-[#F9F9F9]"></div>
                        <div class="w-[1px] h-24 bg-[#F9F9F9]/30"></div>
                    </div>
                    <div class="pb-8">
                        <p class="label-tiny text-[#F9F9F9] font-bold">02. Payment Verified</p>
                        <p class="font-sans text-xs text-neutral-400 mt-1">Success via {{ $order->payment_method }}</p>
                    </div>
                </div>
                
                <!-- Phase 3: Roasting & Packing -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4 h-full">
                        @if ($order->status === 'Paid')
                            <!-- Current Active Phase -->
                            <div class="w-2.5 h-2.5 bg-green-500 animate-pulse"></div>
                        @else
                            <!-- Completed Phase -->
                            <div class="w-2.5 h-2.5 bg-[#F9F9F9]"></div>
                        @endif
                        <div class="w-[1px] h-24 bg-[#F9F9F9]/30"></div>
                    </div>
                    <div class="pb-8">
                        @if ($order->status === 'Paid')
                            <div class="inline-block bg-green-950/60 text-green-400 border border-green-800 px-2 py-0.5 mb-2 label-tiny text-[9px] font-bold">CURRENT PHASE</div>
                        @endif
                        <p class="label-tiny {{ $order->status === 'Paid' ? 'text-[#F9F9F9]' : 'text-[#F9F9F9]' }} font-bold">03. In Roast Schedule</p>
                        <p class="font-sans text-xs text-neutral-400 mt-1">
                            @if ($order->status === 'Paid')
                                Roasting profile calibration at Tuban Roastery. Est. dispatch: 24h.
                            @else
                                Roasting and packaging successfully completed.
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Phase 4: Dispatched -->
                <div class="flex items-start">
                    <div class="flex flex-col items-center mr-4 h-full">
                        @if ($order->status === 'Shipped')
                            <div class="w-2.5 h-2.5 bg-green-500 animate-pulse"></div>
                        @else
                            <div class="w-2.5 h-2.5 border border-[#444444] bg-[#121212]"></div>
                        @endif
                    </div>
                    <div>
                        @if ($order->status === 'Shipped')
                            <div class="inline-block bg-green-950/60 text-green-400 border border-green-800 px-2 py-0.5 mb-2 label-tiny text-[9px] font-bold">CURRENT PHASE</div>
                        @endif
                        <p class="label-tiny {{ $order->status === 'Shipped' ? 'text-[#F9F9F9] font-bold' : 'opacity-40' }}">04. Dispatched with Tracking</p>
                        <p class="font-sans text-xs {{ $order->status === 'Shipped' ? 'text-neutral-400' : 'text-neutral-500' }} mt-1">
                            @if ($order->status === 'Shipped')
                                Package handed over to courier. Your tracking number is active.
                            @else
                                Standard Shipping
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Order Details Summary -->
        <div class="col-span-12 lg:col-span-7 lg:pl-12 space-y-12">
            <!-- Items Wrapper -->
            <div id="order-items-wrapper" class="space-y-6">
                <h2 class="label-tiny opacity-60 mb-6 font-bold">Your Beans</h2>
                
                @foreach ($order->items as $item)
                    <!-- Dynamic Product Row -->
                    <div class="flex gap-4 items-center">
                        <div class="w-16 h-20 bg-[#1a1a1a] border border-[#F9F9F9]/10 overflow-hidden flex-shrink-0">
                            <!-- Backup default coffee packaging mockup image -->
                            <img class="w-full h-full object-cover grayscale brightness-90" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDb1xpGK5YLtJgXI-Ej1XU8ac6A9zyZ3XMT2g1GnouDhIOXyC-Fh84tjYy6_XxxAxnRgIQNWAC7YknFDTODxK-LmO33YfLZkTikQ2NqiTIx13hRNaJ_l5JBC_6eXsTpsGE5SaZhf-jW8qhKaqWnrC6r0exbZgrfWGpxCAKsHrS3IGSDSs8d2qdXT1iynwNKSuA0ZasXNXXWld-R4vJZkRF5jPsQlHUonMDp1PeAJAQdY4q6g0xNazyE_dgGTy_s46dg1Fdd0H1og7w" alt="{{ $item->product_name }}">
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <h4 class="font-display text-sm uppercase italic leading-tight text-on-background">{{ $item->product_name }}</h4>
                                <p class="label-tiny text-[9px] opacity-50 mt-1 text-neutral-400">{{ $item->grind_size }} | QTY: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-sans text-xs font-semibold mt-2 text-neutral-200">Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Totals & Address -->
            <div class="border-t border-[#F9F9F9]/10 pt-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-sans text-sm">
                    <div>
                        <span class="label-tiny opacity-60 block mb-2 font-bold">Subtotal</span>
                        <span id="order-subtotal" class="font-semibold text-neutral-200">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="label-tiny opacity-60 block mb-2 font-bold">Shipping</span>
                        <span id="order-shipping" class="font-semibold text-neutral-200">
                            {{ $order->shipping_cost == 0 ? 'FREE' : 'Rp. ' . number_format($order->shipping_cost, 0, ',', '.') }}
                        </span>
                    </div>
                    <div>
                        <span class="label-tiny opacity-60 block mb-2 font-bold">Total Paid</span>
                        <span id="order-total" class="font-display text-2xl text-on-background">Rp. {{ number_format($order->total_paid, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-[#F9F9F9]/10 pt-8 text-sm">
                    <div>
                        <span class="label-tiny opacity-60 block mb-2 font-bold">Ship To</span>
                        <address id="order-shipping-address" class="not-italic text-neutral-300 leading-relaxed">
                            {{ $order->first_name }} {{ $order->last_name }}<br/>
                            {{ $order->shipping_address }}<br/>
                            {{ $order->city }}, {{ $order->postal_code }}
                        </address>
                        @if ($order->order_notes)
                            <div class="mt-4 p-4 bg-[#1a1a1a]/30 border border-[#F9F9F9]/5">
                                <span class="label-tiny opacity-60 block mb-1 text-[9px] font-bold">Instructions</span>
                                <p class="font-sans text-neutral-400 text-xs italic">{{ $order->order_notes }}</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="label-tiny opacity-60 block mb-2 font-bold">Payment Method</span>
                        <p id="order-payment-method" class="text-neutral-300">{{ $order->payment_method }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
