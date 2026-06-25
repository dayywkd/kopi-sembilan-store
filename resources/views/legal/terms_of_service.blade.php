@extends('layouts.app')

@section('title', 'Terms of Service | Toko Kopi Sembilan')
@section('meta_description', 'Syarat dan ketentuan penggunaan website serta pembelian produk di Toko Kopi Sembilan.')

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-4xl mx-auto text-[#121212]">
    <header class="border-b border-[#E5E7EB] pb-8 mb-10">
        <span class="label-tiny text-neutral-500 block mb-2">Legal</span>
        <h1 class="font-display text-5xl md:text-7xl uppercase italic leading-none">Terms of Service</h1>
    </header>

    <div class="space-y-8 font-sans text-sm leading-relaxed text-neutral-600">
        <section>
            <h2 class="label-tiny text-[#121212] font-bold mb-3">Ketentuan Umum</h2>
            <p>Dengan menggunakan website Toko Kopi Sembilan, Anda menyetujui proses pembelian, pembayaran manual, pengiriman, dan kebijakan layanan yang berlaku.</p>
        </section>

        <section>
            <h2 class="label-tiny text-[#121212] font-bold mb-3">Pesanan & Pembayaran</h2>
            <p>Semua pembayaran dilakukan secara manual melalui Bank Transfer atau QRIS. Pesanan diproses setelah bukti pembayaran diverifikasi oleh admin.</p>
        </section>

        <section>
            <h2 class="label-tiny text-[#121212] font-bold mb-3">Pengiriman</h2>
            <p>Biaya dan estimasi pengiriman mengikuti layanan kurir yang tersedia. Nomor resi akan ditampilkan pada halaman tracking saat pesanan sudah dikirim.</p>
        </section>

        <section>
            <h2 class="label-tiny text-[#121212] font-bold mb-3">Produk Kopi</h2>
            <p>Profil rasa, roast level, dan informasi origin disediakan sebagai panduan. Perbedaan karakter rasa dapat terjadi karena metode seduh, penyimpanan, dan preferensi personal.</p>
        </section>

        <section>
            <h2 class="label-tiny text-[#121212] font-bold mb-3">Perubahan Ketentuan</h2>
            <p>Toko Kopi Sembilan dapat memperbarui ketentuan ini sewaktu-waktu untuk menyesuaikan operasional toko dan kebutuhan pelanggan.</p>
        </section>
    </div>
</main>
@endsection
