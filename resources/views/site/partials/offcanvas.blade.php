<!-- at-offcanvus-area-start -->
    <div class="at-offcanvas-area">
        <div class="at-offcanvas">
            <div class="at-offcanvas-top !flex !items-center !justify-between">
                <div class="at-offcanvas-logo">
                    <a href="{{ route('home') }}">
                        <img data-width="30" src="{{ asset('assets/imgs/template/logo/favicon.png') }}" alt="ESCAT">
                        <p class="h6 fw-700 fz-24 !mb-0">ESCAT</p>
                    </a>
                </div>
                <div class="at-offcanvas-close-btn">
                    <button class="close-btn">
                        <svg width="37" height="38" viewBox="0 0 37 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.19141 9.80762L27.5762 28.1924" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.19141 28.1924L27.5762 9.80761" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="at-offcanvas-content !hidden xl:!block">
                <h3 class="at-offcanvas-title">Bienvenue !</h3>
                <p class="fz-font-lg">L’École Supérieure de Commerce, d’Administration et de Technologie forme, à Brazzaville, les techniciens et cadres dont les entreprises du Congo ont besoin. Deux pôles, quatre cycles, une pédagogie par projet.</p>
            </div>
            <div class="at-offcanvas-menu xl:!hidden pb-50">
                <nav></nav>
            </div>
            <div class="at-offcanvas-gallery !hidden xl:!block">
                <div class="sec-2-home-5__avatars-row !flex !gap-2">
                    <div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ asset('assets/imgs/template/avatar/avatar-10.webp') }}" alt="ESCAT">
                    </div>
                    <div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ asset('assets/imgs/template/avatar/avatar-11.webp') }}" alt="ESCAT">
                    </div>
                    <div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ asset('assets/imgs/template/avatar/avatar-12.webp') }}" alt="ESCAT">
                    </div>
                    <div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ asset('assets/imgs/template/avatar/avatar-13.webp') }}" alt="ESCAT">
                    </div>
                    <div class="sec-2-home-5__avatar-sm at-offcanvas-gallery-img">
                        <img class="img-cover" src="{{ asset('assets/imgs/template/avatar/avatar-14.webp') }}" alt="ESCAT">
                    </div>
                </div>
            </div>
            <div class="at-offcanvas-contact">
                <h4 class="h5 at-offcanvas-title sm">Nous joindre</h4>
                <ul>
                    <li><a class="fz-font-lg" href="tel:+242068848344">+242 06 884 83 44</a></li>
                    <li><a class="fz-font-lg" href="mailto:hello@aleric.com">infos@escat.org</a></li>
                    <li>
                        <a class="fz-font-lg" href="{{ route('about') }}">
                                                        Brazzaville, République du Congo
                        </a>
                    </li>
                </ul>
            </div>
            <div class="at-offcanvas-social">
                <h3 class="at-offcanvas-title sm">Nous suivre</h3>
                <ul class="at-offcanvas-social__grid">
                    <x-site.reseaux style="grille" />
                </ul>
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>
    <!-- at-offcanvus-area-end -->
