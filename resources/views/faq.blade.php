@extends('layouts.app')

@section('title', 'Frequently Asked Questions (FAQ) | Toko Kopi Sembilan')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }
    
    /* FAQ Accordion Styling */
    .faq-item {
        border-bottom: 1px solid #E5E7EB;
    }
    .faq-trigger {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 0;
        background: transparent;
        border: none;
        text-align: left;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .faq-trigger:hover {
        color: #d97706;
    }
    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.16, 1, 0.3, 1), padding 0.3s ease;
        padding-bottom: 0;
    }
    .faq-item.active .faq-content {
        max-height: 500px;
        padding-bottom: 24px;
    }
    .faq-icon {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .faq-item.active .faq-icon {
        transform: rotate(45deg);
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen pb-24 bg-white">
    
    {{-- Header --}}
    <section class="px-margin-mobile md:px-margin-desktop py-16 text-center max-w-container-max mx-auto border-b border-[#E5E7EB] mb-16 reveal">
        <span class="label-tiny text-amber-600 block mb-3">FAQ</span>
        <h1 class="font-display text-4xl md:text-6xl uppercase italic text-brand-dark leading-tight">Pertanyaan Umum</h1>
        <p class="font-sans text-sm text-neutral-500 max-w-md mx-auto mt-4 leading-relaxed">
            Temukan jawaban cepat untuk pertanyaan-pertanyaan yang sering diajukan seputar biji kopi kami, pemesanan, dan pengiriman.
        </p>
    </section>

    {{-- FAQ Content Section --}}
    <section class="px-margin-mobile md:px-margin-desktop max-w-3xl mx-auto space-y-2 reveal">
        
        @php
            $faqs = [
                [
                    'q' => 'Bagaimana cara melakukan pemesanan di Toko Kopi Sembilan?',
                    'a' => 'Pilih biji kopi pilihan Anda di menu Shop, tentukan ukuran kemasan dan ukuran gilingan (grind size), masukkan ke keranjang belanja, lalu klik checkout. Setelah mengisi alamat lengkap, Anda dapat memilih metode pembayaran Bank Transfer atau QRIS.'
                ],
                [
                    'q' => 'Apakah biji kopi di sini fresh roasted?',
                    'a' => 'Ya, kami menerapkan sistem fresh roast. Semua pesanan biji kopi akan dipanggang dan dikemas secara khusus oleh tim roaster kami setelah pembayaran Anda terverifikasi, untuk memastikan aroma dan kesegaran rasa terbaik tetap terjaga.'
                ],
                [
                    'q' => 'Berapa lama proses verifikasi pembayaran & pengemasan?',
                    'a' => 'Verifikasi pembayaran dan pengemasan (termasuk proses roasting biji kopi) memakan waktu maksimal 1x24 jam setelah Anda mengonfirmasi pembayaran Anda kepada tim admin kami.'
                ],
                [
                    'q' => 'Bagaimana cara mengonfirmasi pembayaran saya?',
                    'a' => 'Setelah melakukan transfer bank atau scan QRIS, silakan masuk ke halaman pelacakan pesanan atau pembayaran Anda, lalu klik tombol hijau "Kirim Bukti via WhatsApp". Anda akan diarahkan langsung ke WhatsApp admin kami untuk mengirimkan foto bukti transfer Anda secara instan.'
                ],
                [
                    'q' => 'Apakah pesanan saya bisa diambil langsung di toko (Local Pickup)?',
                    'a' => 'Tentu saja! Saat berada di halaman checkout, Anda dapat memilih opsi pengiriman "Ambil di Toko (Local Pickup)". Opsi ini gratis biaya pengiriman, dan pesanan Anda bisa diambil di Roastery kami di Kutorejo, Tuban setelah status pesanan berubah menjadi "Packing".'
                ],
                [
                    'q' => 'Kurir pengiriman apa saja yang didukung?',
                    'a' => 'Kami bekerja sama dengan berbagai layanan kurir pengiriman terpercaya di Indonesia (seperti JNE, J&T, Sicepat, dll.) untuk menjamin paket biji kopi Anda sampai dengan aman dan tepat waktu di seluruh pelosok wilayah Indonesia.'
                ]
            ];
        @endphp

        <div class="border-t border-[#E5E7EB]">
            @foreach($faqs as $index => $faq)
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <span class="font-display text-base md:text-lg italic text-[#121212] pr-4 font-bold">{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined text-neutral-400 faq-icon">add</span>
                    </button>
                    <div class="faq-content">
                        <p class="font-sans text-sm text-neutral-600 leading-relaxed pr-6">
                            {{ $faq['a'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </section>

    {{-- WhatsApp Call to Action Banner --}}
    <section class="px-margin-mobile md:px-margin-desktop max-w-3xl mx-auto mt-20 reveal">
        <div class="bg-brand-cream border border-[#E5E7EB] p-8 md:p-12 text-center space-y-6">
            <span class="material-symbols-outlined text-amber-600 text-4xl">help_outline</span>
            <div class="space-y-2">
                <h3 class="font-display text-2xl md:text-3xl italic text-[#121212] font-bold">Punya Pertanyaan Lain?</h3>
                <p class="font-sans text-xs md:text-sm text-neutral-500 max-w-md mx-auto leading-relaxed">
                    Jika Anda memiliki pertanyaan khusus, keluhan, atau membutuhkan bantuan pemesanan wholesale B2B, tim support kami siap membantu Anda secara langsung via WhatsApp.
                </p>
            </div>
            <div>
                <a href="https://wa.me/6285336688839?text=Halo%20Toko%20Kopi%20Sembilan%2C%20saya%20ingin%20bertanya%20mengenai..." 
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-3 bg-[#25D366] text-white hover:bg-[#128C7E] font-bold text-xs uppercase tracking-widest px-8 py-4 transition-all duration-300 active:scale-[0.98]">
                    <svg class="fill-current" style="width:16px;height:16px;" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.498 1.452 5.43 1.453 5.414 0 9.82-4.402 9.823-9.817.002-2.624-1.02-5.09-2.88-6.953-1.862-1.863-4.33-2.887-6.954-2.889-5.422 0-9.827 4.404-9.831 9.82-.001 1.943.506 3.841 1.47 5.509l-.965 3.526 3.61-.947zm11.231-6.793c-.302-.152-1.791-.883-2.073-.985-.282-.102-.489-.152-.696.152-.207.304-.799.985-.979 1.187-.18.203-.361.228-.663.077-1.127-.565-1.928-1.01-2.697-2.327-.2-.343.2-.319.574-1.066.06-.122.03-.228-.015-.319-.045-.091-.489-1.18-.671-1.616-.177-.428-.356-.369-.489-.376-.127-.007-.272-.008-.418-.008-.145 0-.382.054-.582.273-.2.22-.763.746-.763 1.82 0 1.073.782 2.107.891 2.254.11.147 1.54 2.349 3.729 3.291.52.224.926.358 1.242.459.522.167 1.002.143 1.379.087.42-.063 1.291-.527 1.472-1.034.18-.506.18-.94.127-1.034-.053-.09-.203-.152-.505-.304z"/>
                    </svg>
                    Tanya Via WhatsApp
                </a>
            </div>
        </div>
    </section>

</main>
@endsection

@section('scripts')
<script>
    function toggleFaq(button) {
        const item = button.parentElement;
        const isActive = item.classList.contains('active');
        
        // Tutup semua FAQ item lainnya
        document.querySelectorAll('.faq-item').forEach(el => {
            el.classList.remove('active');
        });
        
        // Toggle item yang diklik
        if (!isActive) {
            item.classList.add('active');
        }
    }
</script>
@endsection
