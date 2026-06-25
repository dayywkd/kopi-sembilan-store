<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja {{ $order->transaction_id }} | Toko Kopi Sembilan</title>
    
    <!-- Google Fonts untuk UI Luar Struk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- html2canvas CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        :root {
            --bg-color: #0d0e10;
            --card-bg: #16181c;
            --accent-color: #d97706;
            --text-color: #f3f4f6;
            --text-muted: #9ca3af;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Tampilan Struk Kertas Kasir */
        .receipt-wrapper {
            background-color: #ffffff;
            color: #000000;
            width: 380px;
            max-width: 100%;
            padding: 40px 30px;
            box-sizing: border-box;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            margin-bottom: 30px;
            /* Desain tepi struk bergerigi di bawah */
            background-image: linear-gradient(135deg, transparent 50%, #ffffff 50%), linear-gradient(225deg, transparent 50%, #ffffff 50%);
            background-position: bottom left, bottom left;
            background-size: 10px 10px;
            background-repeat: repeat-x;
            padding-bottom: 50px;
        }

        /* Konten dalam struk menggunakan font mesin kasir */
        .receipt-content {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.5;
            color: #000000;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-title {
            font-family: 'Times New Roman', Times, Georgia, serif;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            display: inline-block;
            border-bottom: 4px double #000000;
            padding-bottom: 2px;
            margin: 0 auto 12px auto;
        }

        .receipt-address {
            font-size: 11px;
            line-height: 1.4;
            max-width: 280px;
            margin: 0 auto;
            text-align: center;
        }

        .receipt-meta {
            margin-top: 15px;
            font-size: 13px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .receipt-divider {
            border-top: 1px dashed #000000;
            margin: 12px 0;
            height: 0;
        }

        /* Items List */
        .receipt-items {
            margin: 10px 0;
        }

        .item-block {
            margin-bottom: 12px;
        }

        .item-name {
            font-weight: bold;
            font-size: 13px;
        }

        .item-detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-top: 2px;
        }

        /* Total Section */
        .receipt-total-section {
            margin: 10px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
        }

        /* Receipt Footer */
        .receipt-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            line-height: 1.4;
        }

        .receipt-footer-brand {
            font-weight: bold;
            margin-top: 8px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Panel Kontrol Action */
        .actions-panel {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 380px;
            max-width: 100%;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: none;
            box-sizing: border-box;
        }

        .btn-download {
            background-color: var(--accent-color);
            color: #ffffff;
        }

        .btn-download:hover {
            background-color: #b45309;
            transform: translateY(-2px);
        }

        .btn-download:active {
            transform: translateY(0);
        }

        .btn-back {
            background-color: transparent;
            color: var(--text-color);
            border: 1px solid var(--text-muted);
        }

        .btn-back:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            border-color: #ffffff;
        }

        .btn-back span {
            font-size: 18px;
        }

        /* Status Toast */
        .toast {
            position: fixed;
            bottom: 30px;
            background-color: rgba(22, 24, 28, 0.95);
            color: #ffffff;
            border: 1px solid var(--accent-color);
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 420px) {
            .receipt-wrapper {
                width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            .actions-panel {
                width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- WRAPPER STRUK YANG AKAN DI-EXPORT -->
        <div id="receipt-card" class="receipt-wrapper">
            <div class="receipt-content">
                
                <!-- HEADER STRUK -->
                <div class="receipt-header">
                    <div class="receipt-title">TOKO KOPI SEMBILAN</div>
                    <div class="receipt-address">
                        Jl. Pemuda Kutorejo Gg. II No.230, Kutorejo, Kec. Tuban, Kabupaten Tuban, Jawa Timur 62311
                        <br>
                        WA: 085336688839
                    </div>
                </div>
                
                <!-- METADATA TRANSAKSI -->
                <div class="receipt-meta">
                    <div class="meta-row">
                        <span>Tgl: {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                        <span>Jam: {{ $order->created_at->timezone('Asia/Jakarta')->format('H.i') }}</span>
                    </div>
                    <div class="meta-row">
                        <span>ID: {{ $order->transaction_id }}</span>
                        <span>Admin Kopi Sembilan</span>
                    </div>
                </div>
                
                <div class="receipt-divider"></div>
                
                <!-- DAFTAR ITEM BELANJA -->
                <div class="receipt-items">
                    @foreach ($order->items as $item)
                        <div class="item-block">
                            <div class="item-name">{{ $item->product_name }} - {{ $item->grind_size }}</div>
                            <div class="item-detail-row">
                                <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                <span>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="receipt-divider"></div>
                
                <!-- RINGKASAN BIAYA -->
                <div class="receipt-total-section">
                    @if($order->shipping_cost > 0)
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="total-row">
                            <span>Ongkir</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="receipt-divider"></div>
                    @endif
                    
                    <div class="total-row grand-total">
                        <span>TOTAL</span>
                        <span>Rp {{ number_format($order->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="total-row" style="margin-top: 4px;">
                        <span>Metode: {{ $order->payment_method }}</span>
                        <span></span>
                    </div>
                </div>
                
                <div class="receipt-divider"></div>
                
                <!-- FOOTER STRUK -->
                <div class="receipt-footer">
                    @if ($order->status === 'Awaiting Payment')
                        <div>Silakan Selesaikan Pembayaran Anda</div>
                    @else
                        <div>Terima Kasih Telah Berbelanja</div>
                    @endif
                    <div class="receipt-footer-brand">KOPI SEMBILAN</div>
                </div>
                
            </div>
        </div>
        
        <!-- PANEL TOMBOL ACTION -->
        <div class="actions-panel">
            <button onclick="downloadReceiptImage()" class="btn btn-download">
                <span class="material-symbols-outlined">download</span>
                Unduh Foto Struk
            </button>
            <a href="{{ route('order.tracking', $order->uuid) }}" class="btn btn-back">
                <span class="material-symbols-outlined">arrow_back</span>
                Kembali ke Pesanan
            </a>
        </div>
        
    </div>

    <!-- TOAST NOTIFIKASI -->
    <div id="toast" class="toast">
        <span class="material-symbols-outlined" style="color: var(--accent-color)">hourglass_empty</span>
        <span>Sedang menyiapkan gambar struk...</span>
    </div>

    <script>
        function showToast(message, iconName = 'hourglass_empty', duration = 3000) {
            const toast = document.getElementById('toast');
            toast.querySelector('span:nth-child(2)').textContent = message;
            toast.querySelector('.material-symbols-outlined').textContent = iconName;
            toast.classList.add('show');
            
            if (duration > 0) {
                setTimeout(() => {
                    toast.classList.remove('show');
                }, duration);
            }
        }

        function hideToast() {
            document.getElementById('toast').classList.remove('show');
        }

        function downloadReceiptImage() {
            showToast('Sedang membuat gambar struk...', 'sync');
            
            const receiptCard = document.getElementById('receipt-card');
            
            // Konfigurasi html2canvas untuk memastikan kualitas dan aspek rendering yang tajam
            const options = {
                scale: 3, // Perbesar skala gambar untuk kualitas cetak/tampilan resolusi tinggi
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false
            };
            
            // Lakukan konversi HTML ke Canvas
            html2canvas(receiptCard, options).then(canvas => {
                try {
                    // Konversi Canvas ke Data URL PNG
                    const imgData = canvas.toDataURL('image/png');
                    
                    // Buat elemen link virtual untuk download
                    const link = document.createElement('a');
                    link.download = 'struk-kopi-sembilan-{{ $order->transaction_id }}.png';
                    link.href = imgData;
                    
                    // Trigger klik unduhan
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    showToast('Struk berhasil diunduh sebagai gambar!', 'check_circle', 3000);
                } catch (error) {
                    console.error('Gagal membuat gambar struk:', error);
                    showToast('Gagal mengunduh gambar struk. Coba lagi.', 'error', 4000);
                }
            }).catch(error => {
                console.error('Kesalahan html2canvas:', error);
                showToast('Terjadi kesalahan sistem.', 'error', 4000);
            });
        }
    </script>
</body>
</html>
