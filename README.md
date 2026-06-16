# Toko Kopi Sembilan - Specialty Coffee Roastery E-Commerce

Toko Kopi Sembilan adalah platform e-commerce premium yang dikembangkan khusus untuk roastery kopi spesialis yang berbasis di Tuban, Indonesia. Platform ini dirancang dengan estetika ultra-minimalist dark mode untuk memberikan pengalaman belanja yang eksklusif dan profesional bagi para penikmat kopi.

## 🚀 Teknologi yang Digunakan

- **Framework:** Laravel 12 (Monolithic MVC)
- **Frontend:** Blade Templating, Tailwind CSS / Vanilla CSS, JavaScript
- **Database:** MySQL
- **Integrasi Logistik:** Biteship API (Pengiriman & Pencarian Wilayah)
- **Styling:** Premium Dark Theme (#121212)

## ✨ Fitur Utama

### 🛒 Pengalaman Belanja (Buyer Side)
- **Katalog Kopi Spesialis:** Navigasi produk dengan spesifikasi teknis lengkap (Roast Level, Altitude, Sensory Notes).
- **Filter Dinamis:** Kategori produk mulai dari `SINGLE ORIGIN`, `ESPRESSO BLENDS`, hingga `GEAR`.
- **Custom Grind Size:** Pilihan ukuran gilingan yang terintegrasi (Whole Bean, Espresso, Pour Over, French Press).
- **Integrasi Biteship (Smart Shipping):**
  - Pencarian wilayah (Kecamatan, Kota, atau Kode Pos) terintegrasi otomatis dengan Biteship API.
  - Perhitungan ongkos kirim real-time berdasarkan berat belanjaan dan area tujuan.
  - Logika gratis ongkir otomatis (Free Shipping Threshold) untuk pembelian di atas Rp 500.000.
- **Formulir Checkout Aman:**
  - Validasi ketat nama, email, alamat, wilayah, dan pilihan kurir.
  - Input **Nomor HP / Telepon** yang wajib diisi untuk pengguna login maupun tamu (guest) untuk keperluan kurir pengiriman.
- **Halaman Instruksi Pembayaran Dinamis:**
  - **Bank Transfer (BCA):** Menyediakan nomor rekening, langkah transfer, dan total pembayaran dengan 3 digit kode unik acak untuk verifikasi otomatis/manual yang mudah.
  - **QRIS:** Menyediakan kode QR statis toko dan langkah-langkah pembayaran instan menggunakan e-wallet (GoPay, OVO, Dana, dll).
  - Dilengkapi tombol konfirmasi otomatis / pengiriman bukti bayar via WhatsApp.
- **Order Tracking:** Sistem pelacakan pesanan real-time dari status `Awaiting Payment` (dengan banner "Bayar Sekarang"), `Paid`, `Packing`, `Shipped` (menampilkan nomor resi), hingga `Delivered`.

### 🛠️ Manajemen Operasional (Admin Side)
- **Fulfillment Dashboard:** Ringkasan jumlah pesanan yang menunggu dipanggang (Awaiting Roast), volume pengiriman (Shipped Volume), rata-hari waktu fulfillment, dan total pendapatan.
- **Order Management:** Manajemen status pesanan secara efisien (mengubah status, input nomor resi pengiriman).
- **Verifikasi Pembayaran Manual:** Fitur cepat untuk verifikasi lunas pesanan yang masih berstatus `Awaiting Payment`.
- **Print Receipt & Resi:** Fitur cetak invoice serta label pengiriman (resi) yang memuat nama, alamat lengkap, dan nomor telepon penerima demi kelancaran ekspedisi.

## ⚙️ Instalasi Lokal

1. Clone repositori:
   ```bash
   git clone https://github.com/dayywkd/kopi-sembilan-store.git
   ```
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Instal dependensi JavaScript:
   ```bash
   npm install && npm run build
   ```
4. Salin file lingkungan:
   ```bash
   cp .env.example .env
   ```
5. Konfigurasi file `.env` (pastikan DB koneksi dan API key Biteship sudah dimasukkan):
   ```env
   BITESHIP_API_KEY=your_biteship_api_key
   ```
6. Generate application key:
   ```bash
   php artisan key:generate
   ```
7. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   ```
8. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

## ☁️ Deployment (Railway)
Aplikasi ini sudah dioptimalkan untuk berjalan di platform cloud seperti Railway dengan penyesuaian:
- Mengaktifkan pengalihan skema HTTPS (`forceScheme('https')`) secara otomatis ketika berada di lingkungan produksi (`production`) untuk menghindari masalah mixed content dan peringatan *"This form is not secure"*.
- Mengonfigurasi trust proxies (`trustProxies(at: '*')`) agar Laravel mengenali header SSL/TLS termination yang diteruskan oleh load balancer Railway.

## 📐 Arsitektur & Desain
Proyek ini mengikuti standar **SCA (Specialty Coffee Association)** dalam penyajian informasi produk, memastikan setiap detail teknis biji kopi tersampaikan dengan akurat kepada pelanggan.

---
*Est. MMXXIV — Precision Roasting. Pure Expression.*
