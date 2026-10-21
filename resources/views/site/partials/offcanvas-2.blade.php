<!-- offcanvas 2 start -->
    <div class="at-offcanvas-2-area">
        <div class="offcanvas-bg"></div>
        <div class="at-offcanvas-2-wrapper offcanvas-menu">
            <div class="at-offcanvas-2-left">
                <div class="at-header-logo !flex !justify-between !items-center mb-50">
                    <a href="{{ route('home') }}">
                        <img class="dark-mode-invert" data-width="80" src="{{ asset('assets/imgs/template/logo/favicon-dark.png') }}" alt="ESCAT">
                        {{-- <p class="h6 fw-700 fz-24 !mb-0">ESCAT</p> --}}
                    </a>
                    <span class="hamburger-close-btn">
                        <svg width="37" height="38" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
                <div class="at-offcanvas-menu counter-row">
                    <nav></nav>
                </div>
                <div class="at-offcanvas-social">
                    <ul class="at-offcanvas-social__grid">
                        <x-site.reseaux style="grille" />
                    </ul>
                </div>
                <span class="hamburger-close-btn hamburger-mobile-close-btn md:!hidden d-md-none">FERMER</span>
            </div>
        </div>
    </div>
    <!-- offcanvas 2 end -->
