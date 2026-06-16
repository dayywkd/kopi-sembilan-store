<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Resi & Invoice #{{ $order->transaction_id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #ffffff;
            color: #000000;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px dashed #000000;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000000;
            padding-bottom: 15px;
        }
        .meta-col {
            flex: 1;
        }
        .meta-col:last-child {
            text-align: right;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000000;
            padding-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 8px 0;
        }
        th {
            border-bottom: 1px solid #000000;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-left: auto;
            width: 300px;
            margin-bottom: 30px;
        }
        .totals table td {
            padding: 4px 0;
        }
        .totals .grand-total {
            border-top: 1px solid #000000;
            border-bottom: 2px double #000000;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            border-top: 1px dashed #000000;
            padding-top: 15px;
        }
        .no-print-btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto 0 auto;
            padding: 10px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .no-print-btn:hover {
            background-color: #333333;
        }
        
        /* Shipping Label Format */
        .shipping-label {
            margin-top: 40px;
            border: 2px solid #000000;
            padding: 20px;
            page-break-before: always;
        }
        .shipping-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .shipping-title {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .shipping-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        .address-box {
            border: 1px solid #000000;
            padding: 10px;
            min-height: 100px;
        }
        .address-box h4 {
            margin: 0 0 8px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #000000;
            padding-bottom: 3px;
        }
        .address-box p {
            margin: 4px 0;
            font-size: 13px;
        }
        .shipping-meta {
            border: 1px solid #000000;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }
            .container {
                border: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align: center; margin-bottom: 20px;">
    <button onclick="window.print()" class="no-print-btn">Cetak Dokumen</button>
    <a href="{{ route('admin.dashboard') }}" style="display: inline-block; margin-top: 10px; color: #000000; font-size: 12px;">&larr; Kembali ke Dashboard</a>
</div>

<div class="container">
    <!-- INVOICE SECTION -->
    <div class="header">
        <h1>TOKO KOPI SEMBILAN</h1>
        <p>Email: owner@kopisembilan.com | Telp: +62 812-3456-7890</p>
    </div>

    <div class="meta-info">
        <div class="meta-col">
            <strong>PESANAN UNTUK:</strong><br>
            {{ $order->first_name }} {{ $order->last_name }}<br>
            {{ $order->email }}
        </div>
        <div class="meta-col">
            <strong>NOMOR TRANSAKSI:</strong> #{{ $order->transaction_id }}<br>
            <strong>TANGGAL:</strong> {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB<br>
            <strong>METODE BAYAR:</strong> {{ $order->payment_method }}
        </div>
    </div>

    <div class="section-title">Rincian Belanja</div>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th class="text-right" style="width: 100px;">Harga</th>
                <th class="text-right" style="width: 80px;">Qty</th>
                <th class="text-right" style="width: 120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-right">Rp. {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal Produk:</td>
                <td class="text-right">Rp. {{ number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Pengiriman:</td>
                <td class="text-right">Rp. {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Total Pembayaran:</td>
                <td class="text-right">Rp. {{ number_format($order->total_paid, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    @if($order->order_notes)
        <div class="section-title">Catatan Pembeli</div>
        <p style="font-style: italic; margin: 5px 0 20px 0;">"{{ $order->order_notes }}"</p>
    @endif

    <div class="footer">
        <p>Terima kasih telah berbelanja di Toko Kopi Sembilan!</p>
        <p>Semoga kopi kami menyegarkan hari Anda.</p>
    </div>

    <!-- SHIPPING LABEL SECTION -->
    <div class="shipping-label">
        <div class="shipping-header">
            <h2 class="shipping-title">Label Pengiriman</h2>
            <div style="font-size: 12px; font-weight: bold; border: 1px solid #000; padding: 4px 8px;">
                #{{ $order->transaction_id }}
            </div>
        </div>

        <div class="shipping-grid">
            <div class="address-box">
                <h4>Penerima:</h4>
                <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->city }} - {{ $order->postal_code }}</p>
                @if($order->biteship_area_name)
                    <p style="font-size: 11px; opacity: 0.8;">Area: {{ $order->biteship_area_name }}</p>
                @endif
            </div>
            <div class="address-box">
                <h4>Pengirim:</h4>
                <p><strong>Toko Kopi Sembilan</strong></p>
                <p>Jl. Pemuda Kutorejo Gg. II No.230, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311</p>
                <p>Telp: +62 812-3456-7890</p>
            </div>
        </div>

        <div class="shipping-meta">
            <div><strong>Kurir:</strong> JNE/Biteship (Fulfillment Manual)</div>
            @if($order->tracking_number)
                <div><strong>No. Resi:</strong> {{ $order->tracking_number }}</div>
            @else
                <div><strong>No. Resi:</strong> [Menunggu Input Resi]</div>
            @endif
        </div>
        
        @if($order->order_notes)
            <div style="margin-top: 10px; font-size: 11px; border: 1px dashed #000; padding: 6px;">
                <strong>Catatan Pengiriman:</strong> {{ $order->order_notes }}
            </div>
        @endif
    </div>
</div>

<script>
    // Auto trigger print dialog on page load
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.print();
        }, 500);
    });
</script>
</body>
</html>
