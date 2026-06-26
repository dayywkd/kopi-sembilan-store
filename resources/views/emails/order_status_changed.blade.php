<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Pesanan #{{ $order->transaction_id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f9f9f9;
            color: #121212;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            padding: 40px;
        }
        .header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 24px;
            margin-bottom: 24px;
            text-align: center;
        }
        .logo {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        .title {
            font-size: 22px;
            font-weight: 300;
            margin-top: 16px;
            letter-spacing: -0.02em;
        }
        .status-banner {
            padding: 20px;
            text-align: center;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 32px;
            border: 1px solid #e5e7eb;
        }
        .status-Awaiting-Payment {
            background-color: #f3f4f6;
            color: #1f2937;
        }
        .status-Paid {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        .status-Packing {
            background-color: #fef8e6;
            color: #b45309;
            border-color: #fef08a;
        }
        .status-Shipped {
            background-color: #f0f9ff;
            color: #075985;
            border-color: #bae6fd;
        }
        .status-Delivered {
            background-color: #f0fdf4;
            color: #166534;
            border-color: #bbf7d0;
        }
        .status-Cancelled {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: #fca5a5;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            color: #666666;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        .item-table th {
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px 0;
            border-bottom: 1px solid #121212;
        }
        .item-table td {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .totals-table {
            width: 100%;
            margin-bottom: 32px;
            font-size: 14px;
        }
        .totals-table td {
            padding: 6px 0;
        }
        .totals-table .grand-total {
            border-top: 1px solid #121212;
            font-weight: 700;
            font-size: 16px;
            padding-top: 12px;
        }
        .button-container {
            text-align: center;
            margin-top: 40px;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background-color: #121212;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            transition: all 0.3s ease;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            color: #999999;
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 24px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo">Toko Kopi Sembilan</div>
        <div class="title">Pembaruan Status Pesanan</div>
    </div>

    @php
        $statusName = $order->status;
        $statusClass = 'status-' . str_replace(' ', '-', $order->status);
        $statusDescription = 'Status pesanan Anda telah diperbarui.';
        
        switch ($order->status) {
            case 'Paid':
                $statusName = 'Pembayaran Diterima';
                $statusDescription = 'Terima kasih! Pembayaran Anda telah kami terima dan diverifikasi. Pesanan Anda kini masuk ke dalam antrean persiapan.';
                break;
            case 'Packing':
                $statusName = 'Sedang Diproses';
                $statusDescription = 'Pesanan Anda sedang dalam proses penyiapan dan pemanggangan (roasting) agar selalu fresh saat dikirim.';
                break;
            case 'Shipped':
                $statusName = 'Telah Dikirim';
                $trackingText = $order->tracking_number ? ' dengan nomor resi ' . $order->tracking_number : '';
                $statusDescription = 'Kabar baik! Pesanan Anda telah diserahkan ke kurir pengiriman' . $trackingText . '. Silakan lacak pengiriman Anda secara berkala.';
                break;
            case 'Delivered':
                $statusName = 'Telah Diterima';
                $statusDescription = 'Pesanan Anda telah ditandai sebagai diterima. Terima kasih telah berbelanja di Toko Kopi Sembilan! Bagikan momen menyeduh Anda bersama kami.';
                break;
            case 'Cancelled':
                $statusName = 'Dibatalkan / Kedaluwarsa';
                $statusDescription = 'Pesanan Anda telah dibatalkan (tidak menerima pembayaran dalam 24 jam atau dibatalkan oleh admin). Jika Anda ingin memesan kembali, silakan kunjungi website kami.';
                break;
        }
    @endphp

    <div class="status-banner {{ $statusClass }}">
        Status Pesanan: {{ $statusName }}
    </div>

    <p style="font-size: 14px; line-height: 1.6; color: #4b5563; margin-bottom: 32px;">
        {{ $statusDescription }}
    </p>

    <table style="width: 100%; margin-bottom: 24px; font-size: 14px;">
        <tr>
            <td>
                <strong>Penerima / Penerimaan:</strong><br>
                {{ $order->first_name }} {{ $order->last_name }}<br>
                @if(str_contains(strtolower($order->shipping_address), 'ambil di toko'))
                    <em>Ambil Mandiri di Roastery Toko Kopi Sembilan</em>
                @else
                    {{ $order->shipping_address }}<br>
                    {{ $order->city }} - {{ $order->postal_code }}
                @endif
                <br>Telp: {{ $order->phone }}
            </td>
            <td style="text-align: right; vertical-align: top;">
                <strong>Order ID:</strong> #{{ $order->transaction_id }}<br>
                <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }} WIB<br>
                <strong>Metode Bayar:</strong> {{ $order->payment_method }}
            </td>
        </tr>
    </table>

    <div class="section-title">Item Belanja</div>
    <table class="item-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Harga</th>
                <th class="text-right" style="width: 60px;">Qty</th>
                <th class="text-right" style="width: 100px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }} ({{ $item->grind_size }})</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal Produk</td>
            <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Biaya Pengiriman</td>
            <td class="text-right">
                {{ $order->shipping_cost == 0 ? 'GRATIS' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}
            </td>
        </tr>
        <tr class="grand-total">
            <td>Total Pembayaran</td>
            <td class="text-right">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="button-container">
        <a href="{{ $order->uuid ? route('order.tracking', $order->uuid) : route('order.tracking.form') }}" class="btn">Lacak Status Pesanan</a>
    </div>

    <div class="footer">
        <p>© 2026 Toko Kopi Sembilan. Jl. Pemuda Kutorejo Gg. II No.230, Tuban, Jawa Timur.</p>
        <p>Email ini dikirim secara otomatis untuk memberikan informasi mengenai status transaksi Anda.</p>
    </div>
</div>

</body>
</html>
