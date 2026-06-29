<footer class="bg-brand-gray border-t border-brand-outline pt-20 pb-12 text-brand-dark font-sans">
    <div class="w-full px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto space-y-16">
        
        <!-- Top Section: Socials & Newsletter -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-8 pb-12 border-b border-neutral-200">
            <!-- Social Icons (Left) -->
            <div class="flex items-center gap-5">
                <a href="https://www.instagram.com/tokokopisembilan" target="_blank" aria-label="Instagram" class="text-brand-dark hover:text-brand-accent transition-colors">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <a href="https://wa.me/6285336688839" target="_blank" aria-label="WhatsApp" class="text-brand-dark hover:opacity-80 transition-opacity">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.458 5.704 1.459h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>
            </div>

            <!-- Newsletter (Right) -->
            <div class="flex flex-col items-center md:items-end gap-3 text-center md:text-right w-full md:w-auto">
                <span class="text-xs text-neutral-500 uppercase tracking-widest font-semibold">{{ __trans('Berlangganan & dapatkan diskon 10% untuk pesanan kopi pertama Anda.', 'Subscribe & receive 10% off your first coffee order.') }}</span>
                <span class="font-display text-xl font-bold italic text-brand-dark -mt-1">{{ __trans('Bergabung dengan Keluarga Toko Kopi Sembilan', 'Join the Toko Kopi Sembilan Family') }}</span>
                <form onsubmit="event.preventDefault(); showToast('{{ __trans('Terima kasih telah berlangganan!', 'Thank you for subscribing!') }}');" class="flex border border-neutral-300 rounded-full overflow-hidden p-1 bg-white w-full max-w-md items-center mt-1">
                    <input type="email" placeholder="{{ __trans('Masukkan alamat email Anda', 'Enter your email address') }}" class="flex-grow px-5 py-2 text-xs bg-white text-brand-dark placeholder:opacity-50 focus:ring-0 focus:outline-none" style="border: none !important; outline: none !important; box-shadow: none !important; margin: 0 !important;" required />
                    <button type="submit" class="bg-brand-dark text-white px-6 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-widest hover:bg-brand-accent transition-colors">
                        {{ __trans('Daftar', 'Sign up') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Middle Section: Column Navigation -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-y-12 gap-x-8 py-4">
            <!-- Column 1: About -->
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-brand-dark">{{ __trans('Tentang', 'About') }}</h4>
                <div class="flex flex-col gap-2 text-xs text-brand-dark font-medium">
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('about') }}">{{ __trans('Cerita Kami', 'Our Story') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('location') }}">{{ __trans('Lokasi', 'Locations') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('wholesale') }}">{{ __trans('Kemitraan', 'Wholesale') }}</a>
                </div>
            </div>

            <!-- Column 2: Shop -->
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-brand-dark">{{ __trans('Belanja', 'Shop') }}</h4>
                <div class="flex flex-col gap-2 text-xs text-brand-dark font-medium">
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('shop') }}">{{ __trans('Beli Kopi', 'Shop Coffee') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('reviews') }}">{{ __trans('Ulasan', 'Reviews') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('faq') }}">{{ __trans('Tanya Jawab', 'FAQ') }}</a>
                </div>
            </div>

            <!-- Column 3: Support -->
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-brand-dark">{{ __trans('Bantuan', 'Support') }}</h4>
                <div class="flex flex-col gap-2 text-xs text-brand-dark font-medium">
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('order.tracking.form') }}">{{ __trans('Lacak Pesanan', 'Track Order') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('legal.refund') }}">{{ __trans('Kebijakan Pengembalian', 'Refund Policy') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('legal.privacy') }}">{{ __trans('Kebijakan Privasi', 'Privacy Policy') }}</a>
                    <a class="hover:text-brand-accent transition-colors" href="{{ route('legal.terms') }}">{{ __trans('Syarat & Ketentuan', 'Terms & Conditions') }}</a>
                </div>
            </div>

            <!-- Column 4: Visit Us -->
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-brand-dark">{{ __trans('Kunjungi Kami', 'Visit Us') }}</h4>
                <p class="text-xs text-brand-dark leading-relaxed font-medium">
                    Jl. Pemuda Kutorejo Gg. II No.230<br/>
                    Kutorejo, Tuban, {!! __trans('Jawa Timur', 'East Java') !!} 62311<br/>
                    Indonesia
                </p>
            </div>

            <!-- Column 5: Operating Hours -->
            <div class="flex flex-col gap-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-brand-dark">{{ __trans('Jam Operasional', 'Operating Hours') }}</h4>
                <div class="flex flex-col gap-2 text-xs text-brand-dark font-medium">
                    <p>{{ __trans('Sel-Kam: 10.00-15.00, 17.00-23.00', 'Tue-Thu: 10am-3pm, 5pm-11pm') }}</p>
                    <p>{{ __trans('Jum: 14.00-23.00 | Sen: Tutup', 'Fri: 2pm-11pm | Mon: Closed') }}</p>
                    <p>{{ __trans('Sab-Min: 07.00-15.00, 17.00-23.00', 'Sat-Sun: 7am-3pm, 5pm-11pm') }}</p>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Metadata -->
        <div class="border-t border-neutral-200 pt-8 text-[11px] font-bold tracking-widest text-brand-dark uppercase">
            <span>&copy; Kopi Sembilan 2026</span>
        </div>
    </div>
</footer>
