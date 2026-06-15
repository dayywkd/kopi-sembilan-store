<footer class="bg-[#121212] pt-stack-xl pb-12 flex flex-col justify-end">
    <div class="w-full px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="flex flex-col md:flex-row flex-wrap justify-between gap-y-12 gap-x-8 mb-16 w-full">
            <div class="flex flex-col gap-6">
                <h4 class="label-tiny opacity-60">SHOP</h4>
                <div class="flex flex-col gap-2">
                    <a class="label-tiny nav-link-hover opacity-60" href="{{ route('shop') }}">all collections</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="{{ route('shop', ['category' => 'espresso-blends']) }}">espresso blends</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="{{ route('shop', ['category' => 'single-origin']) }}">single origin</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="{{ route('cart.index') }}">find your order</a>
                </div>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="label-tiny opacity-60">COMMUNITY</h4>
                <div class="flex flex-col gap-2">
                    <a class="label-tiny nav-link-hover opacity-60" href="#">brew guide</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="#">events</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="#">partners &amp; wholesale</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="#">sourcing</a>
                </div>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="label-tiny opacity-60">CONTACT</h4>
                <div class="flex flex-col gap-2">
                    <a class="label-tiny nav-link-hover opacity-60" href="{{ route('about') }}">about us</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="#">our founders</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="https://www.instagram.com/tokokopisembilan" target="_blank">instagram</a>
                    <a class="label-tiny nav-link-hover opacity-60" href="tel:+6285336688839">phone</a>
                </div>
            </div>
            <div class="flex flex-col gap-6">
                <h4 class="label-tiny opacity-60">VISIT US</h4>
                <div class="flex flex-col gap-6">
                    <p class="label-tiny leading-relaxed opacity-70">
                        JL. PEMUDA KUTOREJO GG. II NO.230<br/>
                        KUTOREJO, TUBAN, EAST JAVA 62311<br/>
                        INDONESIA
                    </p>
                    <div class="flex flex-col gap-2">
                        <p class="label-tiny opacity-60">OPERATING HOURS</p>
                        <p class="label-tiny opacity-70">TUE-THU: 10AM-3PM, 5PM-11PM</p>
                        <p class="label-tiny opacity-70">FRI: 2PM-11PM | MON: CLOSED</p>
                        <p class="label-tiny opacity-70">SAT-SUN: 7AM-3PM, 5PM-11PM</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-[#F9F9F9]/10 flex flex-col md:flex-row justify-between items-center gap-8 pt-12 w-full">
            <div class="flex items-center gap-6">
                <img alt="Logo" class="h-8 w-auto" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA5Zl4WTYXRM8SeVHW73IhchoB-59p6vOOk_zUD-x01Lz1LAaph09x0HTbpTSAK-YCie5KpEsXbfZOOHWunBhIRYyrtFSt9C8woWaAC60SEIOwKZnrNS1JuRrLKQh4ExgGDOXuV72Pd2C8lXKQKA7-Snma9rxoY0BbLYPuXSGoe-8CFY7Rf_R1uvkJAqPsEs9uYQ3Wa02ptoD3GcOvZjpQ61YAPC-_N_VZqm9lQmrs9OLlkBCs5CIH3uO_ZPWX2XaaeieKzKpqLGik"/>
                <p class="label-tiny opacity-60">© 2024-2026 TOKO KOPI SEMBILAN</p>
            </div>
            <div class="flex gap-8">
                <a class="label-tiny nav-link-hover opacity-60" href="#">PRIVACY</a>
                <a class="label-tiny nav-link-hover opacity-60" href="#">TERMS</a>
                <a class="label-tiny nav-link-hover opacity-60" href="#">WHOLESALE</a>
                <a class="label-tiny nav-link-hover opacity-60" href="#">CONTACT</a>
            </div>
        </div>
    </div>
</footer>
