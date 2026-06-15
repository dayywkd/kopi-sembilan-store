# PRODUCT REQUIREMENT DOCUMENT (PRD) — TOKO KOPI SEMBILAN
**Stack:** Laravel 11 (Monolithic MVC), Blade, JavaScript, MySQL
**Theme & Aesthetics:** Ultra-minimalist premium dark mode (Background: #121212, Text: #F9F9F9)

## 1. RUANG LINGKUP PRODUK (SISI PEMBELI)
*   **Katalog & Navigasi Utama:**
    *   Menampilkan halaman utama (`home.html`) dengan transisi estetik premium dan sistem pengarsipan koleksi biji kopi.
    *   Halaman toko (`shop.html`) wajib menyediakan sistem filter dinamis berdasarkan kategori koleksi: `SINGLE ORIGIN`, `ESPRESSO BLENDS`, `ROAST PROFILE`, `GEAR`, dan `SUBSCRIPTIONS`.
    *   Pencarian produk berbasis teks langsung di katalog kopi dan urutan berdasarkan `NEWEST`, `PRICE LOW-HIGH`, dan `ALPHABETICAL`.
*   **Halaman Detail Produk (`detailproduk.html`):**
    *   Menampilkan spesifikasi teknis biji kopi: Roast Level (Light, Medium-Light, Medium, Medium-Dark), Ketinggian Tanam (Altitude), dan Sensory Notes.
    *   Menyediakan pilihan variasi Ukuran Gilingan (*Grind Size*) wajib melalui elemen selector: `WHOLE BEAN`, `ESPRESSO (FINE)`, `POUR OVER (MEDIUM)`, dan `FRENCH PRESS (COARSE)`.
*   **Manajemen Keranjang Belanja (`keranjang.html`):**
    *   Penyimpanan data keranjang belanja (*cart counter & state*) menggunakan sinkronisasi local storage / session Laravel.
    *   Menyediakan text-area khusus bernama `order-notes` untuk menampung instruksi penyeduhan atau permintaan khusus dari pembeli.
*   **Proses Checkout (`checkout.html`) & Aturan Ongkir:**
    *   Form pengiriman wajib menangkap data entri: `first-name`, `last-name`, `email`, `address`, `city`, dan `postal-code`.
    *   Metode pembayaran yang didukung di antarmuka wajib dipetakan ke backend: `Bank Transfer` (Verifikasi Manual) dan `QRIS` (Proses Instan via Payment Gateway).
    *   **Aturan Logika Ongkir:** Bebas biaya kirim (*Free Shipping*) otomatis diterapkan jika total belanjaan di atas atau sama dengan Rp 500.000. Jika di bawah nominal tersebut, dikenakan biaya flat sebesar Rp 15.000.
*   **Halaman Pelacakan Pesanan Sukses (`shipping.html`):**
    *   Menampilkan ID Transaksi unik berformat `TK9-******`.
    *   Menampilkan visualisasi arsitektur pelacakan pesanan secara bertahap: `01. Order Placed` -> `02. Payment Verified` -> `03. In Roast Schedule` -> `04. Dispatched with Tracking`.

## 2. RUANG LINGKUP SISTEM (SISI ADMIN PANEL)
*   **Manajemen Pemenuhan Pesanan (`admindashboard.html`):**
    *   Dashboard ringkasan data finansial dan operasional: `Awaiting Roast` (Total antrean pesanan masuk), `Shipped Volume` (Jumlah terkirim), `Avg. Fulfillment`, dan `Revenue (Total)`.
    *   Sistem manajemen order untuk mengubah status pemenuhan pesanan secara real-time via modal popup: `Paid`, `Packing`, dan `Shipped`.
    *   Fitur cari data pesanan berdasarkan ID Transaksi, Nama Pelanggan, atau Email Pelanggan langsung dari bilah atas dashboard admin.