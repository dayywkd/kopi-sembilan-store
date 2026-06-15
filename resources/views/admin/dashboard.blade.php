@extends('layouts.admin')

@section('title', 'Admin Dashboard | Toko Kopi Sembilan')

@section('content')
<!-- Top App Bar -->
<header class="h-20 border-b border-[#444444] flex items-center justify-between px-10">
    <div class="flex items-center gap-6">
        <h2 class="font-display text-2xl uppercase italic">Order Management</h2>
        <div class="h-6 w-[1px] bg-[#444444]"></div>
        <span class="label-tiny opacity-60">Real-time Fulfillment</span>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative">
            <input id="search-input" onkeyup="filterOrders()" class="w-64 py-2 pl-4 pr-10 outline-none text-xs placeholder:opacity-40 uppercase" placeholder="SEARCH ORDERS..." type="text">
            <span class="material-symbols-outlined absolute right-3 top-2.5 text-xs opacity-40">search</span>
        </div>
        <button onclick="clearSearch()" class="border border-[#444444] text-[#F9F9F9] px-6 py-2 label-tiny hover:bg-[#F9F9F9] hover:text-[#121212] hover:border-transparent transition-all active:scale-95">
            Reset Search
        </button>
    </div>
</header>

<!-- Content Canvas -->
<div class="flex-grow overflow-auto p-10">
    <div class="flex flex-col gap-8">
        
        <!-- Status Flash Alert dari update status -->
        @if (session('status_updated'))
            <div class="bg-green-950/60 border border-green-800 text-green-300 px-6 py-4 text-xs uppercase tracking-widest font-bold">
                {{ session('status_updated') }}
            </div>
        @endif

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="border border-[#444444] p-6 bg-[#1a1a1a]/25">
                <span class="label-tiny opacity-60 block mb-2 font-bold">Awaiting Roast</span>
                <span id="stat-awaiting" class="font-display text-4xl font-black">{{ $awaitingRoast }}</span>
            </div>
            <div class="border border-[#444444] p-6 bg-[#1a1a1a]/25">
                <span class="label-tiny opacity-60 block mb-2 font-bold">Shipped Volume</span>
                <span id="stat-shipped" class="font-display text-4xl font-black">{{ $shippedVolume }}</span>
            </div>
            <div class="border border-[#444444] p-6 bg-[#1a1a1a]/25">
                <span class="label-tiny opacity-60 block mb-2 font-bold">Avg. Fulfillment</span>
                <span class="font-display text-4xl font-black">{{ $avgFulfillment }}</span>
            </div>
            <div class="border border-[#444444] bg-[#F9F9F9] text-[#121212] p-6">
                <span class="label-tiny uppercase block mb-2 font-bold">Revenue (Total)</span>
                <span id="stat-revenue" class="font-display text-4xl font-black">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Order Table Container -->
        <div class="border border-[#444444] bg-[#1a1a1a]/10 backdrop-blur-xl">
            <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-[#1a1a1a]/60 border-b border-[#444444] label-tiny opacity-60 text-xs font-bold">
                <div class="col-span-2">Order ID</div>
                <div class="col-span-3">Customer</div>
                <div class="col-span-3">Date & Time</div>
                <div class="col-span-2 text-right">Total Paid</div>
                <div class="col-span-2 text-center">Status</div>
            </div>
            
            <!-- Table Rows -->
            <div id="orders-table-rows" class="divide-y divide-[#444444] text-neutral-300">
                @forelse ($orders as $order)
                    @php
                        $statusClass = 'border-[#444444] text-neutral-400 bg-transparent';
                        if ($order->status === 'Paid') {
                            $statusClass = 'border-[#F9F9F9] text-[#121212] bg-[#F9F9F9]';
                        } elseif ($order->status === 'Packing') {
                            $statusClass = 'border-yellow-600/50 text-yellow-500 bg-yellow-950/20';
                        } elseif ($order->status === 'Shipped') {
                            $statusClass = 'border-green-600/50 text-green-500 bg-green-950/20';
                        }
                    @endphp
                    
                    <div class="grid grid-cols-12 gap-4 px-6 py-5 order-row items-center cursor-pointer" onclick="openTrackingModal('{{ $order->transaction_id }}', '{{ $order->status }}')">
                        <div class="col-span-2 font-mono text-sm font-semibold text-neutral-200">#{{ $order->transaction_id }}</div>
                        <div class="col-span-3">
                            <div class="font-bold text-sm text-[#F9F9F9] group-hover:text-inherit">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="text-xs opacity-60">{{ $order->email }}</div>
                        </div>
                        <div class="col-span-3 text-sm opacity-80">{{ $order->created_at->timezone('Asia/Jakarta')->format('M d, H:i') }}</div>
                        <div class="col-span-2 text-right font-semibold text-sm text-neutral-200">Rp. {{ number_format($order->total_paid, 0, ',', '.') }}</div>
                        <div class="col-span-2 flex justify-center">
                            <span class="px-3 py-1 border text-[9px] uppercase font-bold tracking-widest {{ $statusClass }}">{{ $order->status }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-16 text-center text-neutral-500 font-sans text-sm">
                        No orders in database.
                    </div>
                @endforelse
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 flex justify-between items-center bg-[#1a1a1a]/20 border-t border-[#444444]">
                <span id="pagination-info" class="label-tiny opacity-60 text-xs">Showing {{ $orders->count() }} of {{ $orders->count() }} Results</span>
                <div class="flex gap-2">
                    <button class="p-2 border border-[#444444] hover:border-[#F9F9F9] transition-colors">
                        <span class="material-symbols-outlined text-sm leading-none flex">chevron_left</span>
                    </button>
                    <button class="p-2 border border-[#444444] hover:border-[#F9F9F9] transition-colors">
                        <span class="material-symbols-outlined text-sm leading-none flex">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Footer Meta -->
<footer class="h-12 border-t border-[#444444] flex items-center justify-between px-10 bg-[#121212] text-neutral-500">
    <span class="label-tiny text-[9px] uppercase">© 2026 Toko Kopi Sembilan | System: Optimal</span>
    <div class="flex gap-6">
        <span class="label-tiny text-[9px] uppercase">Node: ROAST-MLG</span>
        <span class="label-tiny text-[9px] uppercase">V 3.1.2-STABLE</span>
    </div>
</footer>

<!-- Modal Overlay for Status Updates -->
<div class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200" id="tracking-modal">
    <div class="bg-[#121212] border border-[#F9F9F9]/20 w-full max-w-md p-8 relative">
        <button class="absolute top-4 right-4 text-[#F9F9F9]/60 hover:text-[#F9F9F9] transition-colors" onclick="closeTrackingModal()">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-display text-2xl mb-2 uppercase italic">Update Order Status</h3>
        <p class="label-tiny opacity-60 mb-6" id="modal-order-id">ORDER: #TK9-000000</p>
        
        <form id="status-update-form" action="{{ route('admin.order.update_status') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="transaction_id" id="modal-transaction-id-input">
            
            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-2 font-bold">Order Status</label>
                <select name="status" id="modal-status-select" class="w-full py-3 px-4 outline-none text-sm uppercase bg-transparent border border-[#444444]">
                    <option class="bg-background text-[#F9F9F9]" value="Paid">Paid</option>
                    <option class="bg-background text-[#F9F9F9]" value="Packing">Packing</option>
                    <option class="bg-background text-[#F9F9F9]" value="Shipped">Shipped</option>
                </select>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full bg-[#F9F9F9] text-[#121212] py-4 font-bold label-tiny hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all">
                    Confirm Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentEditingOrderId = null;

    function openTrackingModal(transactionId, currentStatus) {
        currentEditingOrderId = transactionId;
        document.getElementById('modal-order-id').innerText = `ORDER: #${transactionId}`;
        document.getElementById('modal-transaction-id-input').value = transactionId;
        document.getElementById('modal-status-select').value = currentStatus;

        const modal = document.getElementById('tracking-modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
        }, 10);
    }

    function closeTrackingModal() {
        const modal = document.getElementById('tracking-modal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function filterOrders() {
        const query = document.getElementById('search-input').value.toUpperCase().trim();
        const rows = document.querySelectorAll('.order-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const text = row.textContent.toUpperCase();
            if (text.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('pagination-info').textContent = `Showing ${visibleCount} of ${rows.length} Results`;
    }

    function clearSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.value = '';
            filterOrders();
        }
    }

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTrackingModal();
    });
</script>
@endsection
