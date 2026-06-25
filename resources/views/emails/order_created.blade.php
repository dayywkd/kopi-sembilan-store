<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pesanan #{{ $order->transaction_id }}</title>
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
            font-size: 24px;
            font-weight: 300;
            margin-top: 16px;
            letter-spacing: -0.02em;
        }
        .meta {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #666666;
            margin-bottom: 32px;
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
        <div class="title">Terima kasih atas pesanan Anda.</div>
    </div>

    <table style="width: 100%; margin-bottom: 24px; font-size: 14px;">
        <tr>
            <td>
                <strong>Penerima:</strong><br>
                {{ $order->first_name }} {{ $order->last_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->city }} - {{ $order->postal_code }}<br>
                Telp: {{ $order->phone }}
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
            <td class="text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total">
            <td>Total Pembayaran</td>
            <td class="text-right">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if($order->payment_method === 'Bank Transfer')
        <div style="background-color: #f9f9f9; border: 1px solid #e5e7eb; padding: 20px; font-size: 14px; margin-bottom: 32px;">
            <strong style="text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; display: block; margin-bottom: 8px;">Informasi Pembayaran:</strong>
            Silakan melakukan transfer ke salah satu rekening berikut:<br>
            <strong>Bank BCA: 5550305307 (a.n. Muhammad Fahad)</strong><br>
            <strong>Bank BRI: 010901031684534 (a.n. Muhammad Fahad)</strong><br><br>
            Harap transfer nominal yang sesuai yaitu sebesar:<br>
            <span style="font-size: 18px; font-weight: 700; color: #b45309;">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</span><br>
            <span style="font-size: 12px; color: #666;">(Harap cantumkan kode pesanan {{ $order->transaction_id }} di berita transfer untuk memudahkan verifikasi)</span>
        </div>
    @endif

    <div class="button-container">
        <a href="{{ route('order.tracking', $order->uuid) }}" class="btn">Lacak Status Pesanan</a>
    </div>

    <div class="footer">
        <p>© 2026 Toko Kopi Sembilan. Jl. Pemuda Kutorejo Gg. II No.230, Tuban, Jawa Timur.</p>
        <p>Email ini dikirim secara otomatis sebagai bukti transaksi pembelian Anda.</p>
    </div>
</div>

</body>
</html>
