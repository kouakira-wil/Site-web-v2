<!--search-form-start -->
    <div class="at-search-body-overlay"></div>
    <div class="at-search-form-toggle">
        <div class="container">
            <div class="mb-60 flex flex-wrap -mx-3 [&>*]:px-3">
                <div class="w-full lg:w-full">
                    <div class="at-search-top !flex !justify-between !items-center">
                        <div class="at-header-logo at-search-logo">
                            <a href="{{ route('home') }}">
                                <img data-width="30" src="{{ asset('assets/imgs/template/logo/favicon.png') }}" alt="ESCAT">
                                <p class="h6 fw-700 fz-24 !mb-0">ESCAT</p>
                            </a>
                        </div>
                        <button class="at-search-close">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="!justify-center flex flex-wrap -mx-3 [&>*]:px-3">
                <div class="w-full lg:w-full">
                    <div class="at-search-form">
                        <form action="{{ route('search') }}" method="GET" role="search">
                            <div class="at-search-form-input">
                                <input type="search" name="q" placeholder="Rechercher une filière, un cycle…" aria-label="Rechercher sur le site" autocomplete="off" required>
                                <span class="at-search-focus-border"></span>
                                <button class="at-search-form-btn at-btn" type="submit">
                                    <span>
                                        <span class="text-1">Rechercher</span>
                                        <span class="text-2">Rechercher</span>
                                    </span>
                                    <i class="icon-arrow-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12" fill="none">
                                            <path d="M8.33333 1L13 5.66667M13 5.66667L8.33333 10.3333M13 5.66667H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12" fill="none">
                                            <path d="M8.33333 1L13 5.66667M13 5.66667L8.33333 10.3333M13 5.66667H1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="w-full">
                    <div class="at-categories">
                        <h3 class="at-categories-title">Recherches fréquentes</h3>
                        <ul class="at-categories-list">
                            <li><a href="{{ route('search', ['q' => 'Licence']) }}" class="at-categories-item">Licence</a></li>
                            <li><a href="{{ route('search', ['q' => 'Master']) }}" class="at-categories-item">Master</a></li>
                            <li><a href="{{ route('search', ['q' => 'Réseaux']) }}" class="at-categories-item">Réseaux</a></li>
                            <li><a href="{{ route('search', ['q' => 'Informatique']) }}" class="at-categories-item">Informatique</a></li>
                            <li><a href="{{ route('search', ['q' => 'Comptabilité']) }}" class="at-categories-item">Comptabilité</a></li>
                            <li><a href="{{ route('search', ['q' => 'Finance']) }}" class="at-categories-item">Finance</a></li>
                            <li><a href="{{ route('search', ['q' => 'Électromécanique']) }}" class="at-categories-item">Électromécanique</a></li>
                            <li><a href="{{ route('search', ['q' => 'Admission']) }}" class="at-categories-item">Admission</a></li>
                            <li><a href="{{ route('search', ['q' => 'Préinscription']) }}" class="at-categories-item">Préinscription</a></li>
                            <li><a href="{{ route('search', ['q' => 'Bourses']) }}" class="at-categories-item">Bourses</a></li>
                            <li><a href="{{ route('search', ['q' => 'Stages']) }}" class="at-categories-item">Stages</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- search-form-end -->
