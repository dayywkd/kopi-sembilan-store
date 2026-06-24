@extends('layouts.admin')

@section('title', 'Admin Dashboard | Toko Kopi Sembilan')

@section('content')
<!-- Top App Bar -->
<header class="h-20 border-b border-[#E5E7EB] flex items-center justify-between px-10 bg-white">
    <div class="flex items-center gap-6">
        <!-- Tombol burger menu untuk buka tutup sidebar -->
        <button onclick="toggleSidebar()" class="p-2 border border-brand-outline hover:border-brand-accent hover:text-brand-accent text-neutral-500 transition-colors flex items-center justify-center">
            <span class="material-symbols-outlined text-xl leading-none">menu</span>
        </button>
        
        <h2 id="header-title" class="font-display text-2xl uppercase italic text-[#121212]">Order Management</h2>
        <div class="h-6 w-[1px] bg-[#E5E7EB]"></div>
        <span id="header-subtitle" class="label-tiny opacity-60 text-neutral-500">Real-time Fulfillment</span>
    </div>
    <div class="flex items-center gap-6" id="search-bar-container">
        <div class="relative">
            <input id="search-input" onkeyup="filterActiveTab()" class="w-64 py-2 pl-4 pr-10 outline-none text-xs placeholder:opacity-40 uppercase bg-[#FFFFFF] border border-[#E5E7EB] text-[#121212]" placeholder="SEARCH ORDERS..." type="text">
            <span class="material-symbols-outlined absolute right-3 top-2.5 text-xs opacity-40 text-neutral-500">search</span>
        </div>
        <button onclick="clearActiveSearch()" class="border border-brand-dark text-brand-dark px-6 py-2 label-tiny hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all active:scale-95">
            Reset Search
        </button>
    </div>
</header>

<!-- Content Canvas -->
<div class="flex-grow overflow-auto p-10 bg-white">
    <div class="flex flex-col gap-8">
        
        <!-- Status Flash Alert dari update status -->
        @if (session('status_updated'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 text-xs uppercase tracking-widest font-bold">
                {{ session('status_updated') }}
            </div>
        @endif

        <!-- TAB CONTENT: ORDERS -->
        <div id="content-orders" class="tab-content flex flex-col gap-8">
            <!-- Filter Bar (Foto ke-4) -->
            <div class="flex flex-wrap items-center justify-between gap-4 p-6 border border-brand-outline bg-[#F9F9F9]">
                <div class="flex flex-wrap items-center gap-6">
                    <!-- Filter Tanggal -->
                    <div class="flex items-center gap-2">
                        <span class="label-tiny text-[10px] text-neutral-400 font-bold">TANGGAL:</span>
                        <input type="date" id="filter-date" onchange="filterOrdersTable()" class="py-1 px-3 border border-brand-outline text-xs outline-none bg-white text-[#121212] font-semibold">
                    </div>
                    <!-- Filter Status -->
                    <div class="flex items-center gap-2">
                        <span class="label-tiny text-[10px] text-neutral-400 font-bold">STATUS:</span>
                        <select id="filter-status" onchange="filterOrdersTable()" class="py-1 px-3 border border-brand-outline text-xs outline-none bg-white text-[#121212] font-semibold uppercase">
                            <option value="">Semua Status</option>
                            <option value="AWAITING PAYMENT">Belum Bayar</option>
                            <option value="LUNAS">Lunas</option>
                        </select>
                    </div>
                    <!-- Filter Metode -->
                    <div class="flex items-center gap-2">
                        <span class="label-tiny text-[10px] text-neutral-400 font-bold">METODE:</span>
                        <select id="filter-payment" onchange="filterOrdersTable()" class="py-1 px-3 border border-brand-outline text-xs outline-none bg-white text-[#121212] font-semibold uppercase">
                            <option value="">Semua Metode</option>
                            <option value="TUNAI">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="BANK TRANSFER">Bank Transfer</option>
                        </select>
                    </div>
                </div>
                <!-- Button Export CSV -->
                <button onclick="exportCSV()" class="flex items-center gap-2 border border-brand-dark bg-white text-brand-dark px-4 py-2 text-[10px] font-bold uppercase tracking-wider hover:bg-brand-accent hover:text-white transition-all active:scale-95">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Export CSV
                </button>
            </div>

            <!-- Stats Cards (Foto ke-4 Style but Monochrome) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Pendapatan -->
                <div class="border border-brand-outline p-6 bg-white flex flex-col justify-between">
                    <div>
                        <span class="label-tiny opacity-60 block mb-1 font-bold text-neutral-500">Total Pendapatan</span>
                        <span id="card-revenue" class="font-display text-2xl font-black text-brand-dark">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                    <span id="card-revenue-sub" class="text-[10px] text-neutral-400 mt-4 uppercase tracking-wider font-semibold">Dilihat dari {{ $orders->where('status', '!=', 'Awaiting Payment')->count() }} transaksi</span>
                </div>
                <!-- Rata-rata Transaksi -->
                <div class="border border-brand-outline p-6 bg-white flex flex-col justify-between">
                    <div>
                        <span class="label-tiny opacity-60 block mb-1 font-bold text-neutral-500">Rata-rata Transaksi</span>
                        <span id="card-avg" class="font-display text-2xl font-black text-brand-dark">Rp. {{ number_format($orders->count() > 0 ? $totalRevenue / $orders->count() : 0, 0, ',', '.') }}</span>
                    </div>
                    <span class="text-[10px] text-neutral-400 mt-4 uppercase tracking-wider font-semibold">Per pesanan pelanggan</span>
                </div>
                <!-- Status Lunas -->
                <div class="border border-brand-outline p-6 bg-white flex flex-col justify-between">
                    <div>
                        <span class="label-tiny opacity-60 block mb-1 font-bold text-neutral-500">Status Lunas</span>
                        <span id="card-lunas" class="font-display text-2xl font-black text-brand-dark">{{ $orders->where('status', '!=', 'Awaiting Payment')->count() }}</span>
                    </div>
                    <span id="card-lunas-sub" class="text-[10px] text-neutral-400 mt-4 uppercase tracking-wider font-semibold">{{ $orders->where('status', 'Awaiting Payment')->count() }} Menunggu pembayaran</span>
                </div>
                <!-- Metode Dipilih -->
                <div class="border border-brand-outline p-6 bg-white flex flex-col justify-between">
                    <div>
                        <span class="label-tiny opacity-60 block mb-1 font-bold text-neutral-500">Metode Dipilih</span>
                        <span id="card-metode" class="font-display text-2xl font-black text-brand-dark">Semua</span>
                    </div>
                    <span id="card-metode-sub" class="text-[10px] text-neutral-400 mt-4 uppercase tracking-wider font-semibold">{{ $orders->count() }} Transaksi cocok</span>
                </div>
            </div>

            <!-- Order Table Container (Foto ke-4) -->
            <div class="border border-brand-outline bg-white">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-brand-cream border-b border-brand-outline label-tiny opacity-60 text-xs font-bold text-neutral-500">
                    <div class="col-span-2">ID</div>
                    <div class="col-span-1.5">WAKTU</div>
                    <div class="col-span-2">WHATSAPP</div>
                    <div class="col-span-1.5 text-center">METODE</div>
                    <div class="col-span-1.5 text-center">STATUS</div>
                    <div class="col-span-1.5 text-right">TOTAL</div>
                    <div class="col-span-1 text-right">BAYAR</div>
                    <div class="col-span-1 text-right">KEMBALI</div>
                    <div class="col-span-1 text-center">AKSI</div>
                </div>
                
                <!-- Table Rows -->
                <div id="orders-table-rows" class="divide-y divide-[#E5E7EB] text-neutral-800">
                    @forelse ($orders as $order)
                        @php
                            $isLunas = $order->status !== 'Awaiting Payment';
                            $paymentLabel = $order->payment_method;
                            if (strtolower($paymentLabel) === 'cash' || strtolower($paymentLabel) === 'cash on delivery') {
                                $paymentLabel = 'Tunai';
                            }
                        @endphp
                        
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 order-row items-center cursor-pointer hover:bg-brand-cream transition-colors" 
                             data-date="{{ $order->created_at->format('Y-m-d') }}" 
                             data-status="{{ $isLunas ? 'LUNAS' : 'AWAITING PAYMENT' }}" 
                             data-payment="{{ strtoupper($paymentLabel) }}"
                             onclick="openTrackingModal('{{ $order->transaction_id }}', '{{ $order->status }}', '{{ $order->tracking_number }}')">
                            
                            <!-- ID -->
                            <div class="col-span-2 font-mono text-xs font-semibold text-neutral-900 flex flex-col gap-0.5">
                                <span>#{{ $order->transaction_id }}</span>
                                @if($order->tracking_number)
                                    <span class="text-[9px] text-neutral-400 tracking-normal lowercase">resi: {{ $order->tracking_number }}</span>
                                @endif
                            </div>
                            
                            <!-- WAKTU -->
                            <div class="col-span-1.5 text-xs text-neutral-600">
                                {{ $order->created_at->timezone('Asia/Jakarta')->format('d M, H.i') }}
                            </div>
                            
                            <!-- WHATSAPP -->
                            <div class="col-span-2 text-xs text-neutral-600 font-mono">
                                {{ $order->phone ?? '-' }}
                            </div>
                            
                            <!-- METODE -->
                            <div class="col-span-1.5 text-center flex justify-center">
                                @if($paymentLabel === 'Tunai')
                                    <span class="px-2 py-0.5 border border-brand-dark bg-brand-dark text-white text-[10px] font-bold uppercase tracking-wider">
                                        Tunai
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 border border-[#121212] bg-white text-[#121212] text-[10px] font-bold uppercase tracking-wider">
                                        {{ $paymentLabel }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- STATUS -->
                            <div class="col-span-1.5 text-center flex justify-center">
                                @if($isLunas)
                                    <span class="px-2 py-0.5 border border-[#121212] bg-[#121212] text-white text-[10px] font-bold uppercase tracking-wider">
                                        Lunas
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 border border-brand-outline bg-[#F9F9F9] text-neutral-400 text-[10px] font-bold uppercase tracking-wider">
                                        Belum Bayar
                                    </span>
                                @endif
                            </div>
                            
                            <!-- TOTAL -->
                            <div class="col-span-1.5 text-right font-bold text-xs text-neutral-900">
                                Rp. {{ number_format($order->total_paid, 0, ',', '.') }}
                            </div>
                            
                            <!-- BAYAR -->
                            <div class="col-span-1 text-right text-xs text-neutral-600 font-mono">
                                @if($isLunas)
                                    Rp. {{ number_format($order->total_paid, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </div>
                            
                            <!-- KEMBALI -->
                            <div class="col-span-1 text-right text-xs text-neutral-600 font-mono">
                                -
                            </div>
                            
                            <!-- AKSI -->
                            <div class="col-span-1 flex items-center justify-center gap-1.5" onclick="event.stopPropagation()">
                                <a href="{{ route('admin.order.print', $order->transaction_id) }}" target="_blank"
                                   class="p-1 border border-brand-outline text-neutral-600 hover:bg-brand-dark hover:text-white hover:border-transparent transition-colors flex items-center justify-center"
                                   title="Cetak Resi & Invoice">
                                    <span class="material-symbols-outlined text-[14px]">print</span>
                                </a>
                                @if ($order->status === 'Awaiting Payment')
                                    <form action="{{ route('admin.order.update_status') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="transaction_id" value="{{ $order->transaction_id }}">
                                        <input type="hidden" name="status" value="Paid">
                                        <button type="submit" class="p-1 border border-brand-dark bg-white hover:bg-brand-dark text-brand-dark hover:text-white transition-colors flex items-center justify-center" title="Verifikasi Lunas">
                                            <span class="material-symbols-outlined text-[14px]">done</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center text-neutral-400 font-sans text-sm bg-white">
                            No orders in database.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 flex justify-between items-center bg-brand-cream border-t border-brand-outline">
                    <span id="pagination-info" class="label-tiny opacity-60 text-xs text-neutral-500">Showing {{ $orders->count() }} of {{ $orders->count() }} Results</span>
                    <div class="flex gap-2">
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_left</span>
                        </button>
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: INVENTORY -->
        <div id="content-inventory" class="tab-content hidden flex flex-col gap-8">
            <div class="flex justify-between items-center">
                <h2 class="font-display text-2xl uppercase italic text-brand-dark">Daftar Produk</h2>
                <button onclick="openAddProductModal()" class="flex items-center gap-2 border border-transparent bg-[#121212] text-white px-5 py-3 text-[10px] font-bold uppercase tracking-wider hover:bg-neutral-800 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah Produk Baru
                </button>
            </div>

            <div class="border border-brand-outline bg-white">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-brand-cream border-b border-brand-outline label-tiny opacity-60 text-xs font-bold text-neutral-500">
                    <div class="col-span-1">Gambar</div>
                    <div class="col-span-3">Nama Produk</div>
                    <div class="col-span-2 text-right">Harga</div>
                    <div class="col-span-2 text-center">Stok</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-1 text-center">Best Seller</div>
                    <div class="col-span-2 text-center">Aksi</div>
                </div>
                
                <div class="divide-y divide-[#E5E7EB] text-neutral-800">
                    @forelse($products as $product)
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center product-row">
                            <div class="col-span-1">
                                @if($product->image_path)
                                    <img src="{{ asset($product->image_path) }}" class="h-10 w-10 object-cover border border-brand-outline bg-brand-cream" alt="{{ $product->name }}">
                                @else
                                    <div class="h-10 w-10 bg-brand-cream border border-brand-outline flex items-center justify-center text-[8px] text-neutral-400">No Image</div>
                                @endif
                            </div>
                            <div class="col-span-3 flex flex-col gap-0.5">
                                <span class="font-bold text-sm text-brand-dark">{{ $product->name }}</span>
                                <span class="text-[10px] text-neutral-400 uppercase tracking-widest">{{ $product->category?->name }}</span>
                            </div>
                            <div class="col-span-2 text-right text-sm font-semibold text-neutral-800">Rp. {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="col-span-2 text-center text-sm font-sans font-semibold text-neutral-700">{{ $product->stock }}</div>
                            <div class="col-span-1 text-center">
                                @if ($product->stock > 0 && $product->status !== 'SOLD OUT')
                                    <span class="px-2 py-0.5 border border-green-200 bg-green-50 text-green-700 text-[9px] font-bold uppercase tracking-wider">In Stock</span>
                                @else
                                    <span class="px-2 py-0.5 border border-red-200 bg-red-50 text-red-700 text-[9px] font-bold uppercase tracking-wider">Sold Out</span>
                                @endif
                            </div>
                            <div class="col-span-1 text-center flex justify-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" {{ $product->is_best_seller ? 'checked' : '' }} onchange="toggleBestSeller({{ $product->id }}, this)">
                                    <div class="w-9 h-5 bg-neutral-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-accent"></div>
                                </label>
                            </div>
                            <div class="col-span-2 text-center flex justify-center gap-2">
                                <button onclick="openEditProductModal({{ json_encode($product) }}, '{{ $product->category?->name }}')" class="p-1.5 border border-brand-outline hover:border-brand-accent text-neutral-500 hover:text-brand-accent transition-colors" title="Edit Produk">
                                    <span class="material-symbols-outlined text-[16px] leading-none flex">edit</span>
                                </button>
                                <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 border border-brand-outline hover:border-red-500 text-neutral-500 hover:text-red-500 transition-colors" title="Hapus Produk">
                                        <span class="material-symbols-outlined text-[16px] leading-none flex">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center text-neutral-400 font-sans text-sm bg-white">
                            Belum ada produk terdaftar.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 flex justify-between items-center bg-brand-cream border-t border-brand-outline">
                    <span id="inventory-pagination-info" class="label-tiny opacity-60 text-xs text-neutral-500">Showing {{ $products->count() }} of {{ $products->count() }} Results</span>
                    <div class="flex gap-2">
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_left</span>
                        </button>
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: CUSTOMERS -->
        <div id="content-customers" class="tab-content hidden flex flex-col gap-8">
            <div class="border border-brand-outline bg-white">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-brand-cream border-b border-brand-outline label-tiny opacity-60 text-xs font-bold text-neutral-500">
                    <div class="col-span-3">Nama Lengkap</div>
                    <div class="col-span-3">Email</div>
                    <div class="col-span-2">No. Telepon</div>
                    <div class="col-span-2">Alamat Utama</div>
                    <div class="col-span-2 text-center">Tanggal Gabung</div>
                </div>
                
                <div class="divide-y divide-[#E5E7EB] text-neutral-800">
                    @forelse($customers as $customer)
                        <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center customer-row">
                            <div class="col-span-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand-dark text-white flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($customer->name, 0, 2) }}
                                </div>
                                <span class="font-bold text-sm text-brand-dark">{{ $customer->name }}</span>
                            </div>
                            <div class="col-span-3 text-sm text-neutral-600 font-mono">{{ $customer->email }}</div>
                            <div class="col-span-2 text-sm text-neutral-600 font-mono">{{ $customer->phone ?? '-' }}</div>
                            <div class="col-span-2 text-xs text-neutral-500 leading-snug">
                                @if($customer->address)
                                    {{ Str::limit($customer->address, 35) }}{{ $customer->city ? ', ' . $customer->city : '' }}
                                @else
                                    <span class="italic text-neutral-400">Belum diisi</span>
                                @endif
                            </div>
                            <div class="col-span-2 text-center text-xs text-neutral-500">{{ $customer->created_at->format('d M Y') }}</div>
                        </div>
                    @empty
                        <div class="p-16 text-center text-neutral-400 font-sans text-sm bg-white">
                            No customers registered yet.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Footer -->
                <div class="px-6 py-4 flex justify-between items-center bg-brand-cream border-t border-brand-outline">
                    <span id="customers-pagination-info" class="label-tiny opacity-60 text-xs text-neutral-500">Showing {{ $customers->count() }} of {{ $customers->count() }} Results</span>
                    <div class="flex gap-2">
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_left</span>
                        </button>
                        <button class="p-2 border border-brand-outline hover:border-brand-accent transition-colors">
                            <span class="material-symbols-outlined text-sm leading-none flex text-neutral-500 hover:text-brand-accent">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: ANALYTICS -->
        <div id="content-analytics" class="tab-content hidden flex flex-col gap-8">
            <!-- Stats Overview Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="border border-brand-outline p-6 bg-brand-cream">
                    <span class="label-tiny opacity-60 block mb-2 font-bold text-neutral-500">Total Pendapatan</span>
                    <span class="font-display text-3xl font-black text-brand-dark">Rp. {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="border border-brand-outline p-6 bg-brand-cream">
                    <span class="label-tiny opacity-60 block mb-2 font-bold text-neutral-500">Jumlah Transaksi</span>
                    <span class="font-display text-3xl font-black text-brand-dark">{{ $orders->count() }}</span>
                </div>
                <div class="border border-brand-outline p-6 bg-brand-cream">
                    <span class="label-tiny opacity-60 block mb-2 font-bold text-neutral-500">Total Pelanggan</span>
                    <span class="font-display text-3xl font-black text-brand-dark">{{ $customers->count() }}</span>
                </div>
                <div class="border border-brand-accent p-6 bg-brand-cream">
                    <span class="label-tiny uppercase block mb-2 font-bold text-neutral-500">Pesanan Diproses</span>
                    <span class="font-display text-3xl font-black text-brand-accent">{{ $awaitingRoast }}</span>
                </div>
            </div>

            <!-- Detail Analysis Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kopi Terpopuler (Popular Beans) -->
                <div class="border border-brand-outline bg-white p-8">
                    <h3 class="font-display text-lg uppercase italic mb-6 text-brand-dark border-b border-brand-outline pb-4">Kopi Terpopuler</h3>
                    <div class="space-y-6">
                        @foreach($products->take(4) as $index => $prod)
                            @php
                                $percentages = [85, 65, 45, 30];
                                $percent = $percentages[$index] ?? 20;
                            @endphp
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-xs font-semibold uppercase tracking-wider text-neutral-700">
                                    <span>{{ $prod->name }}</span>
                                    <span>{{ $percent }}% Popularity</span>
                                </div>
                                <div class="w-full h-2.5 bg-neutral-100">
                                    <div class="h-2.5 bg-[#121212] transition-all" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Transaksi Terbaru (Recent Fulfilment) -->
                <div class="border border-brand-outline bg-white p-8">
                    <h3 class="font-display text-lg uppercase italic mb-6 text-brand-dark border-b border-brand-outline pb-4">Aktivitas Terkini</h3>
                    <div class="divide-y divide-[#E5E7EB]">
                        @foreach($orders->take(4) as $ord)
                            <div class="py-3 flex justify-between items-center">
                                <div class="flex flex-col gap-0.5">
                                    <span class="font-mono text-xs font-bold text-brand-dark">#{{ $ord->transaction_id }}</span>
                                    <span class="text-[10px] text-neutral-400 uppercase tracking-widest">{{ $ord->first_name }} {{ $ord->last_name }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-semibold text-neutral-800">Rp. {{ number_format($ord->total_paid, 0, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 border text-[9px] font-bold uppercase tracking-wider border-neutral-200 text-neutral-500 bg-neutral-50">{{ $ord->status }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: COURIERS -->
        <div id="content-couriers" class="tab-content hidden flex flex-col gap-8">
            <form action="{{ route('admin.settings.update_couriers') }}" method="POST" class="border border-brand-outline bg-white p-8 max-w-2xl space-y-6">
                @csrf
                <div>
                    <h3 class="font-display text-lg uppercase italic mb-2 text-brand-dark">Pilih Kurir Biteship Aktif</h3>
                    <p class="text-xs text-neutral-500 mb-6 font-semibold uppercase tracking-wider">Membatasi opsi pengiriman yang ditampilkan kepada pembeli pada halaman checkout.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $availableCouriers = [
                                'jne' => 'JNE Express',
                                'jnt' => 'J&T Express',
                            ];
                        @endphp
                        @foreach($availableCouriers as $key => $name)
                            <label class="flex items-center gap-3 p-4 border border-brand-outline hover:bg-brand-cream cursor-pointer transition-colors">
                                <input type="checkbox" name="couriers[]" value="{{ $key }}" {{ in_array($key, $activeCouriers) ? 'checked' : '' }} class="text-brand-accent focus:ring-brand-accent border-brand-outline">
                                <span class="text-sm font-bold text-neutral-800">{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div class="pt-4 border-t border-brand-outline">
                    <button type="submit" class="bg-brand-dark text-white px-8 py-3 font-bold label-tiny hover:bg-brand-accent border border-brand-dark hover:border-brand-accent transition-all">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Modal Overlay for Status Updates -->
<div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200" id="tracking-modal">
    <div class="bg-white border border-brand-outline shadow-2xl w-full max-w-md p-8 relative">
        <button class="absolute top-4 right-4 text-neutral-400 hover:text-brand-dark transition-colors" onclick="closeTrackingModal()">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-display text-2xl mb-2 uppercase italic text-brand-dark">Update Order Status</h3>
        <p class="label-tiny opacity-60 mb-6 text-neutral-500 font-bold" id="modal-order-id">ORDER: #KS9-000000</p>
        
        <form id="status-update-form" action="{{ route('admin.order.update_status') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="transaction_id" id="modal-transaction-id-input">
            
            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-2 font-bold text-neutral-500">Order Status</label>
                <select name="status" id="modal-status-select" class="w-full py-3 px-4 outline-none text-sm uppercase bg-white border border-brand-outline text-brand-dark focus:border-brand-accent">
                    <option value="Awaiting Payment">Awaiting Payment</option>
                    <option value="Paid">Paid — Terverifikasi</option>
                    <option value="Packing">Packing — Sedang Dikemas</option>
                    <option value="Shipped">Shipped — Dikirim</option>
                    <option value="Delivered">Delivered — Sampai</option>
                </select>
            </div>
            
            <div id="tracking-number-field" class="hidden">
                <label class="label-tiny text-[10px] opacity-60 block mb-2 font-bold text-neutral-500">Nomor Resi Pengiriman</label>
                <input type="text" name="tracking_number" id="modal-tracking-input" class="w-full py-3 px-4 outline-none text-sm uppercase bg-white border border-brand-outline text-brand-dark placeholder:opacity-40 focus:border-brand-accent" placeholder="MASUKKAN NOMOR RESI...">
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full bg-brand-dark text-white py-4 font-bold label-tiny hover:bg-brand-accent border border-brand-dark hover:border-brand-accent transition-all">
                    Confirm Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Add Product -->
<div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200" id="add-product-modal">
    <div class="bg-white border border-brand-outline shadow-2xl w-full max-w-lg p-8 relative overflow-y-auto max-h-[90vh]">
        <button class="absolute top-4 right-4 text-neutral-400 hover:text-brand-dark transition-colors" onclick="closeAddProductModal()">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-display text-2xl mb-6 uppercase italic text-brand-dark">Tambah Produk Baru</h3>
        
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Kategori</label>
                <select name="category_id" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Nama Produk</label>
                <input type="text" name="name" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Harga (Rp)</label>
                    <input type="number" name="price" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Stok</label>
                    <input type="number" name="stock" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Roast Level</label>
                    <select name="roast_level" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                        <option value="Light">Light</option>
                        <option value="Medium-Light">Medium-Light</option>
                        <option value="Medium">Medium</option>
                        <option value="Medium-Dark">Medium-Dark</option>
                    </select>
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Ketinggian (Altitude)</label>
                    <input type="text" name="altitude" placeholder="Contoh: 1500m" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Sensory Notes</label>
                <input type="text" name="sensory_notes" placeholder="Contoh: Jasmine, Apple, Caramel" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Ukuran & Harga (JSON)</label>
                <textarea name="sizes" required class="w-full py-2 px-3 text-xs bg-white border border-brand-outline text-brand-dark h-16 font-mono" placeholder='[{"size":"100gr","price":78000}]'>[{"size":"100gr","price":78000}]</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Status</label>
                    <select name="status" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                        <option value="AVAILABLE">AVAILABLE</option>
                        <option value="LIMITED">LIMITED</option>
                        <option value="SOLD OUT">SOLD OUT</option>
                    </select>
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Gambar Produk</label>
                    <input type="file" name="image" class="w-full text-xs text-brand-dark">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#121212] text-white py-3.5 font-bold label-tiny hover:bg-neutral-800 transition-all">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Overlay for Edit Product -->
<div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200" id="edit-product-modal">
    <div class="bg-white border border-brand-outline shadow-2xl w-full max-w-lg p-8 relative overflow-y-auto max-h-[90vh]">
        <button class="absolute top-4 right-4 text-neutral-400 hover:text-brand-dark transition-colors" onclick="closeEditProductModal()">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-display text-2xl mb-6 uppercase italic text-brand-dark">Edit Detail Produk</h3>
        
        <form id="edit-product-form" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Kategori</label>
                <select name="category_id" id="edit-category_id" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Nama Produk</label>
                <input type="text" name="name" id="edit-name" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Harga (Rp)</label>
                    <input type="number" name="price" id="edit-price" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Stok</label>
                    <input type="number" name="stock" id="edit-stock" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Roast Level</label>
                    <select name="roast_level" id="edit-roast_level" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                        <option value="Light">Light</option>
                        <option value="Medium-Light">Medium-Light</option>
                        <option value="Medium">Medium</option>
                        <option value="Medium-Dark">Medium-Dark</option>
                    </select>
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Ketinggian (Altitude)</label>
                    <input type="text" name="altitude" id="edit-altitude" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                </div>
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Sensory Notes</label>
                <input type="text" name="sensory_notes" id="edit-sensory_notes" required class="w-full py-2 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
            </div>

            <div>
                <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Ukuran & Harga (JSON)</label>
                <textarea name="sizes" id="edit-sizes" required class="w-full py-2 px-3 text-xs bg-white border border-brand-outline text-brand-dark h-16 font-mono"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Status</label>
                    <select name="status" id="edit-status" required class="w-full py-2.5 px-3 text-sm bg-white border border-brand-outline text-brand-dark">
                        <option value="AVAILABLE">AVAILABLE</option>
                        <option value="LIMITED">LIMITED</option>
                        <option value="SOLD OUT">SOLD OUT</option>
                    </select>
                </div>
                <div>
                    <label class="label-tiny text-[10px] opacity-60 block mb-1 font-bold text-neutral-500">Gambar Baru (Opsional)</label>
                    <input type="file" name="image" class="w-full text-xs text-brand-dark">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#121212] text-white py-3.5 font-bold label-tiny hover:bg-neutral-800 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-10 right-10 z-50 flex flex-col gap-2"></div>
@endsection

@section('scripts')
<script>
    let currentEditingOrderId = null;

    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Show selected tab content
        const targetContent = document.getElementById('content-' + tabName);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }
        
        // Reset all sidebar button styles
        document.querySelectorAll('.sidebar-item').forEach(el => {
            el.classList.remove('bg-[#121212]', 'text-[#FFFFFF]', 'font-semibold');
            el.classList.add('text-[#121212]/60', 'hover:text-[#121212]', 'hover:bg-[#F9F9F9]');
        });
        
        // Set active style for selected sidebar button
        const activeSidebarBtn = document.getElementById('sidebar-tab-' + tabName);
        if (activeSidebarBtn) {
            activeSidebarBtn.classList.remove('text-[#121212]/60', 'hover:text-[#121212]', 'hover:bg-[#F9F9F9]');
            activeSidebarBtn.classList.add('bg-[#121212]', 'text-[#FFFFFF]', 'font-semibold');
        }

        // Update Header Title & Subtitle & Search Placeholder secara dinamis agar UI atas konsisten
        const headerTitle = document.getElementById('header-title');
        const headerSubtitle = document.getElementById('header-subtitle');
        const searchInput = document.getElementById('search-input');
        const searchBar = document.getElementById('search-bar-container');

        if (tabName === 'orders') {
            headerTitle.innerText = "Order Management";
            headerSubtitle.innerText = "Real-time Fulfillment";
            searchBar.classList.remove('invisible');
            searchInput.placeholder = "SEARCH ORDERS...";
        } else if (tabName === 'inventory') {
            headerTitle.innerText = "Inventory Control";
            headerSubtitle.innerText = "Katalog & Best Seller";
            searchBar.classList.remove('invisible');
            searchInput.placeholder = "SEARCH PRODUCTS...";
        } else if (tabName === 'customers') {
            headerTitle.innerText = "Customer Database";
            headerSubtitle.innerText = "Registered Users";
            searchBar.classList.remove('invisible');
            searchInput.placeholder = "SEARCH CUSTOMERS...";
        } else if (tabName === 'analytics') {
            headerTitle.innerText = "Performance Analytics";
            headerSubtitle.innerText = "Store Performance Overview";
            searchBar.classList.add('invisible');
        } else if (tabName === 'couriers') {
            headerTitle.innerText = "Courier Configuration";
            headerSubtitle.innerText = "Biteship Integration Settings";
            searchBar.classList.add('invisible');
        }

        // Reset search value on tab switch
        if (searchInput) {
            searchInput.value = '';
            filterActiveTab();
        }

        // Simpan state tab aktif di URL hash
        window.location.hash = tabName;
    }

    function showToast(message, isError = false) {
        const toast = document.createElement('div');
        toast.className = `px-6 py-4 text-xs uppercase tracking-widest font-bold border transition-all duration-300 transform translate-y-2 opacity-0 shadow-lg ${isError ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-50 border-green-200 text-green-800'}`;
        toast.textContent = message;
        
        const container = document.getElementById('toast-container');
        container.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        }, 10);
        
        // Auto dismiss after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function toggleBestSeller(id, checkbox) {
        checkbox.disabled = true;
        fetch(`/admin/products/${id}/toggle-best-seller`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            checkbox.disabled = false;
            if (data.success) {
                showToast(data.message);
            } else {
                checkbox.checked = !checkbox.checked;
                showToast('Gagal memperbarui status best seller.', true);
            }
        })
        .catch(error => {
            checkbox.disabled = false;
            checkbox.checked = !checkbox.checked;
            showToast('Terjadi kesalahan koneksi.', true);
        });
    }

    function toggleTrackingField() {
        const statusSelect = document.getElementById('modal-status-select');
        const trackingField = document.getElementById('tracking-number-field');
        if (statusSelect.value === 'Shipped' || statusSelect.value === 'Delivered') {
            trackingField.classList.remove('hidden');
        } else {
            trackingField.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('modal-status-select').addEventListener('change', toggleTrackingField);

        // Load active tab from hash or query param on initial page load
        const hash = window.location.hash.replace('#', '');
        const validTabs = ['orders', 'inventory', 'customers', 'analytics', 'couriers'];
        
        const urlParams = new URLSearchParams(window.location.search);
        const queryTab = urlParams.get('tab');
        
        let initialTab = 'orders';
        if (validTabs.includes(hash)) {
            initialTab = hash;
        } else if (validTabs.includes(queryTab)) {
            initialTab = queryTab;
        }
        
        switchTab(initialTab);

        // Set default date filter to empty
        document.getElementById('filter-date').value = '';
    });

    function openTrackingModal(transactionId, currentStatus, trackingNumber = '') {
        currentEditingOrderId = transactionId;
        document.getElementById('modal-order-id').innerText = `ORDER: #${transactionId}`;
        document.getElementById('modal-transaction-id-input').value = transactionId;
        document.getElementById('modal-status-select').value = currentStatus;
        document.getElementById('modal-tracking-input').value = trackingNumber || '';
        
        toggleTrackingField();

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

    function filterActiveTab() {
        const query = document.getElementById('search-input').value.toUpperCase().trim();
        
        // Find which tab is active based on sidebar item bg-[#121212] class
        let activeTab = 'orders';
        if (document.getElementById('sidebar-tab-inventory').classList.contains('bg-[#121212]')) {
            activeTab = 'inventory';
        } else if (document.getElementById('sidebar-tab-customers').classList.contains('bg-[#121212]')) {
            activeTab = 'customers';
        }

        if (activeTab === 'orders') {
            filterOrdersTable(); // delegate to complex filtering
        } else if (activeTab === 'inventory') {
            const rows = document.querySelectorAll('.product-row');
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
            document.getElementById('inventory-pagination-info').textContent = `Showing ${visibleCount} of ${rows.length} Results`;
        } else if (activeTab === 'customers') {
            const rows = document.querySelectorAll('.customer-row');
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
            document.getElementById('customers-pagination-info').textContent = `Showing ${visibleCount} of ${rows.length} Results`;
        }
    }

    function filterOrdersTable() {
        const query = document.getElementById('search-input').value.toUpperCase().trim();
        const filterDate = document.getElementById('filter-date').value; // YYYY-MM-DD
        const filterStatus = document.getElementById('filter-status').value;
        const filterPayment = document.getElementById('filter-payment').value;

        const rows = document.querySelectorAll('.order-row');
        let visibleCount = 0;
        let totalRevenue = 0;
        let lunasCount = 0;
        let awaitingCount = 0;
        let matchedCount = 0;

        rows.forEach(row => {
            const date = row.getAttribute('data-date');
            const status = row.getAttribute('data-status');
            const payment = row.getAttribute('data-payment');
            const text = row.textContent.toUpperCase();
            
            // Extract numeric amount from the total paid column (usually 6th column, 1-indexed is 7)
            const totalText = row.querySelector('.col-span-1\\.5:nth-last-child(3)').textContent;
            const amount = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;

            const matchesQuery = text.includes(query);
            const matchesDate = !filterDate || date === filterDate;
            const matchesStatus = !filterStatus || status === filterStatus;
            const matchesPayment = !filterPayment || payment.includes(filterPayment);

            if (matchesQuery && matchesDate && matchesStatus && matchesPayment) {
                row.style.display = '';
                visibleCount++;
                matchedCount++;
                if (status === 'LUNAS') {
                    totalRevenue += amount;
                    lunasCount++;
                } else {
                    awaitingCount++;
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Update statistics cards dynamically
        document.getElementById('card-revenue').textContent = 'Rp. ' + totalRevenue.toLocaleString('id-ID');
        document.getElementById('card-revenue-sub').textContent = `Dilihat dari ${lunasCount} transaksi`;
        
        const avgAmount = matchedCount > 0 ? Math.round(totalRevenue / matchedCount) : 0;
        document.getElementById('card-avg').textContent = 'Rp. ' + avgAmount.toLocaleString('id-ID');
        
        document.getElementById('card-lunas').textContent = lunasCount;
        document.getElementById('card-lunas-sub').textContent = `${awaitingCount} Menunggu pembayaran`;
        
        const paymentLabel = filterPayment ? document.getElementById('filter-payment').value : 'Semua';
        document.getElementById('card-metode').textContent = paymentLabel;
        document.getElementById('card-metode-sub').textContent = `${matchedCount} Transaksi cocok`;

        document.getElementById('pagination-info').textContent = `Showing ${visibleCount} of ${rows.length} Results`;
    }

    function clearActiveSearch() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.value = '';
            filterActiveTab();
        }
    }

    function exportCSV() {
        const rows = document.querySelectorAll('.order-row');
        let csvContent = "data:text/csv;charset=utf-8,ID,Waktu,Whatsapp,Metode,Status,Total\n";
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const id = row.querySelector('.col-span-2').textContent.trim().split('\n')[0];
                const waktu = row.querySelector('.col-span-1\\.5').textContent.trim();
                const wa = row.querySelector('.col-span-2:nth-child(3)').textContent.trim();
                const metode = row.querySelector('.col-span-1\\.5:nth-child(4)').textContent.trim();
                const status = row.querySelector('.col-span-1\\.5:nth-child(5)').textContent.trim();
                const total = row.querySelector('.col-span-1\\.5:nth-child(6)').textContent.trim().replace(/[^0-9]/g, '');
                
                csvContent += `"${id}","${waktu}","${wa}","${metode}","${status}","${total}"\n`;
            }
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `laporan_transaksi_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function openAddProductModal() {
        const modal = document.getElementById('add-product-modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
        }, 50);
    }

    function closeAddProductModal() {
        const modal = document.getElementById('add-product-modal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    function openEditProductModal(product, categoryName) {
        document.getElementById('edit-product-form').action = `/admin/products/${product.id}/update`;
        document.getElementById('edit-category_id').value = product.category_id;
        document.getElementById('edit-name').value = product.name;
        document.getElementById('edit-price').value = Math.round(product.price);
        document.getElementById('edit-stock').value = product.stock;
        document.getElementById('edit-roast_level').value = product.roast_level;
        document.getElementById('edit-altitude').value = product.altitude;
        document.getElementById('edit-sensory_notes').value = product.sensory_notes;
        document.getElementById('edit-sizes').value = JSON.stringify(product.sizes);
        document.getElementById('edit-status').value = product.status;

        const modal = document.getElementById('edit-product-modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
        }, 50);
    }

    function closeEditProductModal() {
        const modal = document.getElementById('edit-product-modal');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endsection
