# Toko Kopi Sembilan - Specialty Coffee Roastery E-Commerce

Toko Kopi Sembilan adalah platform e-commerce premium yang dikembangkan khusus untuk roastery kopi spesialis yang berbasis di Tuban, Indonesia. Platform ini dirancang dengan estetika ultra-minimalist dark mode untuk memberikan pengalaman belanja yang eksklusif dan profesional bagi para penikmat kopi.

## 🚀 Teknologi yang Digunakan

- **Framework:** Laravel 11 (Monolithic MVC)
- **Frontend:** Blade Templating, JavaScript, Vanilla CSS
- **Database:** MySQL / SQLite
- **Styling:** Premium Dark Theme (#121212)

## ✨ Fitur Utama

### 🛒 Pengalaman Belanja (Buyer Side)
- **Katalog Kopi Spesialis:** Navigasi produk dengan spesifikasi teknis lengkap (Roast Level, Altitude, Sensory Notes).
- **Filter Dinamis:** Kategori produk mulai dari `SINGLE ORIGIN`, `ESPRESSO BLENDS`, hingga `GEAR`.
- **Custom Grind Size:** Pilihan ukuran gilingan yang terintegrasi (Whole Bean, Espresso, Pour Over, French Press).
- **Smart Shipping:** Logika pengiriman otomatis (Gratis ongkir untuk pembelian di atas Rp 500.000).
- **Order Tracking:** Sistem pelacakan pesanan real-time dari tahap roasting hingga pengiriman.

### 🛠️ Manajemen Operasional (Admin Side)
- **Fulfillment Dashboard:** Ringkasan antrean roasting, volume pengiriman, dan total pendapatan.
- **Order Management:** Manajemen status pesanan (`Paid`, `Packing`, `Shipped`) secara efisien.
- **Data Centralization:** Pencarian data pelanggan dan riwayat transaksi yang cepat.

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
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   ```
7. Jalankan server:
   ```bash
   php artisan serve
   ```

## 📐 Arsitektur & Desain
Proyek ini mengikuti standar **SCA (Specialty Coffee Association)** dalam penyajian informasi produk, memastikan setiap detail teknis biji kopi tersampaikan dengan akurat kepada pelanggan.

---
*Est. MMXXIV — Precision Roasting. Pure Expression.*
