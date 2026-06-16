@extends('layouts.app')

@section('title', 'Customer Dashboard | Toko Kopi Sembilan')

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
    .order-row {
        transition: all 0.2s ease;
    }
    .order-row:hover {
        background-color: rgba(249, 249, 249, 0.03);
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
    <!-- Header -->
    <header class="mb-stack-lg border-b border-[#F9F9F9]/15 pb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="label-tiny opacity-60 text-xs block mb-2">Customer Portal</span>
            <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none">Your Dashboard</h1>
            <p class="font-sans text-neutral-400 text-sm mt-4">
                Welcome back, <strong class="text-[#F9F9F9]">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
            </p>
        </div>
        <div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="label-tiny border border-[#F9F9F9]/20 px-6 py-3 hover:bg-[#F9F9F9] hover:text-[#121212] hover:border-transparent transition-all duration-300">
                    LOGOUT
                </button>
            </form>
        </div>
    </header>

    @if (session('welcome'))
        <div class="bg-green-950/40 border border-green-800 text-green-300 px-6 py-4 mb-8 text-xs uppercase tracking-widest font-bold">
            {{ session('welcome') }}
        </div>
    @endif

    @if (session('status'))
        <div class="bg-green-950/40 border border-green-800 text-green-300 px-6 py-4 mb-8 text-xs uppercase tracking-widest font-bold">
            {{ session('status') }}
        </div>
    @endif

    <!-- Content Area -->
    <div class="grid grid-cols-12 gap-gutter items-start mt-8">
        <!-- Left Column: Edit Profile & Address -->
        <div class="col-span-12 lg:col-span-4 space-y-6 mb-8 lg:mb-0">
            <h2 class="label-tiny opacity-60 font-bold">Shipping Profile</h2>
            <div class="border border-[#F9F9F9]/10 p-6 bg-[#1a1a1a]/10 backdrop-blur-xl">
                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4 font-sans text-sm text-neutral-300">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="label-tiny text-[10px] opacity-60">Full Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full py-2.5 px-4 outline-none border border-[#444444] text-[#F9F9F9] focus:border-[#F9F9F9] transition-all bg-transparent" required>
                        @error('name')
                            <span class="text-xs text-red-500 uppercase tracking-wider">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="label-tiny text-[10px] opacity-60">Shipping Address</label>
                        <textarea name="address" class="w-full py-2.5 px-4 outline-none border border-[#444444] text-[#F9F9F9] focus:border-[#F9F9F9] transition-all bg-transparent resize-none min-h-[80px]">{{ Auth::user()->address }}</textarea>
                        @error('address')
                            <span class="text-xs text-red-500 uppercase tracking-wider">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-1.5 relative">
                        <label class="label-tiny text-[10px] opacity-60">Cari Wilayah (Kecamatan, Kota, atau Kode Pos)</label>
                        <input id="area-search" type="text" class="w-full py-2.5 px-4 outline-none border border-[#444444] text-[#F9F9F9] focus:border-[#F9F9F9] transition-all bg-transparent" placeholder="Ketik minimal 3 karakter untuk mencari..." autocomplete="off" value="{{ Auth::user()->biteship_area_name ?? '' }}" required>
                        <div id="area-suggestions" class="absolute left-0 right-0 top-[100%] z-50 bg-[#121212] border border-[#444444] max-h-48 overflow-y-auto hidden">
                            <!-- suggestions populated dynamically -->
                        </div>
                        <input type="hidden" name="biteship_area_id" id="biteship-area-id" value="{{ Auth::user()->biteship_area_id }}">
                        <input type="hidden" name="biteship_area_name" id="biteship-area-name" value="{{ Auth::user()->biteship_area_name }}">
                        @error('biteship_area_id')
                            <span class="text-xs text-red-500 uppercase tracking-wider">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="label-tiny text-[10px] opacity-60">City / Kabupaten</label>
                        <input id="city" type="text" name="city" value="{{ Auth::user()->city }}" class="w-full py-2.5 px-4 outline-none border border-[#444444] text-[#F9F9F9] focus:border-[#F9F9F9] transition-all bg-transparent cursor-not-allowed opacity-75" placeholder="Kota / Kabupaten" required readonly>
                        @error('city')
                            <span class="text-xs text-red-500 uppercase tracking-wider">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="label-tiny text-[10px] opacity-60">Postal Code</label>
                        <input id="postal-code" type="text" name="postal_code" value="{{ Auth::user()->postal_code }}" class="w-full py-2.5 px-4 outline-none border border-[#444444] text-[#F9F9F9] focus:border-[#F9F9F9] transition-all bg-transparent cursor-not-allowed opacity-75" placeholder="Kode Pos" required readonly>
                        @error('postal_code')
                            <span class="text-xs text-red-500 uppercase tracking-wider">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="w-full mt-4 bg-[#F9F9F9] text-[#121212] font-bold text-center py-3.5 uppercase tracking-widest hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all text-xs label-tiny">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Order History List -->
        <div class="col-span-12 lg:col-span-8">
            <h2 class="label-tiny opacity-60 mb-6 font-bold">Order History &amp; Tracking</h2>
            
            <div class="border border-[#F9F9F9]/10 bg-[#1a1a1a]/10 backdrop-blur-xl">
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-[#1a1a1a]/60 border-b border-[#F9F9F9]/15 label-tiny opacity-60 text-xs font-bold">
                    <div class="col-span-3 md:col-span-2">Order ID</div>
                    <div class="col-span-4 md:col-span-3">Date &amp; Time</div>
                    <div class="col-span-3 md:col-span-2 text-right">Total Paid</div>
                    <div class="col-span-2 md:col-span-3 text-center">Fulfillment Status</div>
                    <div class="col-span-12 md:col-span-2 text-right mt-2 md:mt-0">Action</div>
                </div>

                <!-- Table Content -->
                <div class="divide-y divide-[#F9F9F9]/10 text-neutral-300">
                    @forelse ($orders as $order)
                        @php
                            $statusClass = 'border-[#444444] text-neutral-400 bg-transparent';
                            if ($order->status === 'Awaiting Payment') {
                                $statusClass = 'border-amber-600/50 text-amber-400 bg-amber-950/20';
                            } elseif ($order->status === 'Paid') {
                                $statusClass = 'border-blue-600/50 text-blue-300 bg-blue-950/20';
                            } elseif ($order->status === 'Packing') {
                                $statusClass = 'border-yellow-600/50 text-yellow-400 bg-yellow-950/20';
                            } elseif ($order->status === 'Shipped') {
                                $statusClass = 'border-green-600/50 text-green-400 bg-green-950/20';
                            } elseif ($order->status === 'Delivered') {
                                $statusClass = 'border-emerald-600/50 text-emerald-300 bg-emerald-950/20';
                            }
                        @endphp
                        <div class="grid grid-cols-12 gap-4 px-6 py-5 order-row items-center">
                            <!-- Order ID -->
                            <div class="col-span-3 md:col-span-2 font-mono text-sm font-semibold text-neutral-200">
                                #{{ $order->transaction_id }}
                            </div>
                            
                            <!-- Date & Time -->
                            <div class="col-span-4 md:col-span-3 text-sm opacity-80">
                                {{ $order->created_at->timezone('Asia/Jakarta')->format('M d, Y — H:i') }}
                            </div>
                            
                            <!-- Total Paid (Unique Code Highlighted via Accessor - Opsi C) -->
                            <div class="col-span-3 md:col-span-2 text-right font-semibold text-sm text-neutral-200">
                                {!! $order->formatted_total_paid !!}
                            </div>
                            
                            <!-- Status -->
                            <div class="col-span-2 md:col-span-3 flex justify-center">
                                <span class="px-3 py-1 border text-[9px] uppercase font-bold tracking-widest {{ $statusClass }}">
                                    @php
                                        $statusLabel = match($order->status) {
                                            'Awaiting Payment' => 'Belum Bayar',
                                            'Paid'             => 'Dibayar',
                                            'Packing'          => 'Dikemas',
                                            'Shipped'          => 'Dikirim',
                                            'Delivered'        => 'Sampai ✓',
                                            default            => $order->status,
                                        };
                                    @endphp
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <!-- Action Link -->
                            <div class="col-span-12 md:col-span-2 text-right">
                                @if ($order->status === 'Awaiting Payment')
                                    <a href="{{ route('order.payment', $order->transaction_id) }}" class="label-tiny text-[10px] text-amber-400 underline underline-offset-4 hover:text-amber-300 transition-colors">
                                        BAYAR
                                    </a>
                                @else
                                    <a href="{{ route('order.tracking', $order->transaction_id) }}" class="label-tiny text-[10px] underline underline-offset-4 hover:text-white transition-colors">
                                        LACAK
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center text-neutral-500 font-sans text-sm">
                            You haven't placed any orders yet. <a href="{{ route('shop') }}" class="text-white underline underline-offset-4">Explore our beans</a>.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
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
                                        <div class="suggestion-item p-3 cursor-pointer border-b border-[#444444]/30 hover:bg-[#F9F9F9] hover:text-[#121212] transition-colors text-xs font-sans" 
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
    });
</script>
@endsection
