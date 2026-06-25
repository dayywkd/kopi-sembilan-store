@extends('layouts.app')

@section('title', 'Kebijakan Privasi | Toko Kopi Sembilan')
@section('meta_description', 'Kebijakan privasi Toko Kopi Sembilan. Ketahui bagaimana kami mengumpulkan, melindungi, dan memperlakukan data pribadi Anda selama transaksi.')

@section('content')
<main class="mt-32 min-h-screen px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto text-[#121212]">
    <div class="max-w-3xl mx-auto space-y-12">
        <div class="border-b border-[#E5E7EB] pb-6 reveal">
            <h1 class="font-display text-5xl md:text-7xl uppercase italic mb-2">Kebijakan Privasi</h1>
            <p class="label-tiny text-neutral-500 font-semibold">Terakhir diperbarui: Juni 2026</p>
        </div>

        <section class="space-y-6 font-sans text-sm leading-relaxed text-neutral-600 reveal">
            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">01 / Informasi yang Kami Kumpulkan</h2>
            <p>
                Toko Kopi Sembilan mengumpulkan informasi yang Anda berikan secara langsung saat melakukan checkout pesanan. Informasi ini meliputi:
            </p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Identitas pribadi: Nama depan dan belakang.</li>
                <li>Kontak pengiriman: Alamat surel (email), nomor telepon/WhatsApp, dan alamat pengiriman fisik.</li>
                <li>Detail pesanan: Jenis produk kopi, grind size, kuantitas, dan pilihan pengiriman.</li>
            </ul>
            <p>
                Kami tidak mengumpulkan informasi kartu kredit atau detail perbankan karena transaksi diselesaikan melalui transfer bank manual BCA atau scan QRIS statis di luar sistem pemrosesan kami.
            </p>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">02 / Penggunaan Informasi</h2>
            <p>
                Informasi yang dikumpulkan digunakan semata-mata untuk:
            </p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Memproses transaksi pembelian Anda.</li>
                <li>Mengatur pengiriman paket kopi Anda melalui kurir rekanan (JNE/J&T) atau mempersiapkan pengambilan mandiri di roastery kami.</li>
                <li>Kebutuhan komunikasi konfirmasi pembayaran dan update status pesanan via WhatsApp atau email otomatis.</li>
            </ul>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">03 / Perlindungan Data</h2>
            <p>
                Kami menerapkan langkah-langkah keamanan teknis untuk menjaga kerahasiaan informasi pribadi Anda. Data Anda disimpan secara aman dalam database kami dan tidak akan pernah dijual, disewakan, atau dibagikan kepada pihak ketiga di luar kebutuhan pemenuhan operasional pengiriman (kurir).
            </p>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">04 / Perubahan Kebijakan</h2>
            <p>
                Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Setiap perubahan akan segera dipublikasikan di halaman ini dengan memperbarui tanggal revisi di bagian atas.
            </p>

            <h2 class="label-tiny text-neutral-800 font-bold border-b border-neutral-200 pb-2">05 / Hubungi Kami</h2>
            <p>
                Jika Anda memiliki pertanyaan mengenai perlindungan data pribadi Anda, silakan hubungi kami melalui alamat email owner@kopisembilan.com atau melalui WhatsApp resmi kami.
            </p>
        </section>
    </div>
</main>
@endsection
