@extends('layouts.app')

@section('title', 'Kebijakan Pengembalian | Toko Kopi Sembilan')
@section('meta_description', 'Kebijakan pengembalian produk biji kopi Toko Kopi Sembilan. Pahami garansi kesegaran produk dan ketentuan klaim komplain Anda.')

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto text-[#121212]">
    <div class="max-w-3xl mx-auto space-y-12">
        <div class="border-b border-[#E5E7EB] pb-6">
            <h1 class="font-display text-5xl md:text-7xl uppercase italic mb-2">Kebijakan Pengembalian</h1>
            <p class="label-tiny text-neutral-500 font-semibold">Terakhir diperbarui: Juni 2026</p>
        </div>

        <section class="space-y-6 font-sans text-sm leading-relaxed text-neutral-600">
            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">01 / Komitmen Kesegaran Produk</h2>
            <p>
                Di Toko Kopi Sembilan, kami berkomitmen menyajikan biji kopi pilihan terbaik yang selalu segar (freshly roasted). Karena sifat produk kopi sebagai komoditas konsumsi makanan/minuman yang mudah berubah rasa tergantung penyimpanan, seluruh pesanan biji kopi yang telah diproses secara umum tidak dapat ditukar atau dikembalikan kecuali terjadi kesalahan dari pihak kami.
            </p>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">02 / Syarat Klaim Kesalahan / Kerusakan</h2>
            <p>
                Anda berhak mendapatkan pengiriman ulang produk atau pengembalian dana (refund) penuh jika:
            </p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Jenis kopi atau varietas produk yang dikirimkan tidak sesuai dengan bukti pesanan Anda.</li>
                <li>Profil gilingan (grind size) yang diterima salah (misal: memesan Whole Bean namun menerima Fine Grind).</li>
                <li>Kemasan produk robek/rusak parah sebelum diserahkan ke kurir pengiriman.</li>
            </ul>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">03 / Prosedur Pengajuan Komplain</h2>
            <p>
                Untuk mengajukan pengembalian, silakan ikuti langkah berikut maksimal <strong>2x24 jam</strong> setelah paket diterima (berdasarkan tracking log kurir):
            </p>
            <ul class="list-decimal pl-6 space-y-2">
                <li>Hubungi customer service kami melalui WhatsApp di +62 858-5518-0131.</li>
                <li>Cantumkan Kode Pesanan Anda (contoh: KS9-XXXXXX).</li>
                <li>Sertakan foto/video unboxing paket sebagai bukti kondisi produk saat pertama kali dibuka.</li>
            </ul>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">04 / Pengembalian Dana (Refund)</h2>
            <p>
                Setelah pengajuan disetujui, kami akan memproses pengembalian dana ke rekening bank Anda dalam waktu maksimal <strong>1-3 hari kerja</strong>. Jika disetujui untuk penggantian barang, kami akan menanggung ongkos kirim produk pengganti tersebut ke alamat Anda.
            </p>
        </section>
    </div>
</main>
@endsection
