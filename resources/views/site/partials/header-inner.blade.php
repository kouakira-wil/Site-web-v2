<!-- header 2 area start -->
        {{-- Bandeau opaque : il précède le contenu au lieu de le survoler. --}}
        <div class="at-header-area at-header-spacing header-solid">
            <div class="container">
                <div class="!items-center flex flex-wrap -mx-3 [&>*]:px-3">
                    <div class="xl:w-1/6 w-1/2">
                        <div class="at-header-logo">
                            <a href="{{ route('home') }}">
                                <img width="150" src="{{ asset('assets/imgs/template/logo/favicon.png') }}" alt="ESCAT">
                                {{-- <p class="h6 fw-700 fz-24 !mb-0">ESCAT</p> --}}
                            </a>
                        </div>
                    </div>
                    <div class="w-full xl:w-2/3 !mx-auto !hidden xl:!flex !justify-center">
                        <div class="at-main-menu !inline-flex !justify-center">
                            <nav class="at-mobile-menu-active">
                                <ul>
                                    <li class="">
                                        <a href="{{ route('home') }}">Accueil</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('about') }}">L’école</a>
                                    </li>
                                    <x-site.filieres-menu />
                                    <li>
                                        <a href="{{ route('team') }}">Équipe</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('posts.index') }}">Actualités</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('contact') }}">Contact</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="xl:w-1/6 w-1/2">
                        <div class="at-header-right !gap-4 !flex !justify-end !items-center">
                            <button class="at-header-search-btn at-search-click">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.7508 18.5233L13.5538 13.392M13.5538 13.392C14.9604 12.0032 15.7506 10.1196 15.7506 8.15551C15.7506 6.19144 14.9604 4.30782 13.5538 2.91902C12.1472 1.53022 10.2395 0.75 8.25028 0.75C6.26108 0.75 4.35336 1.53022 2.94678 2.91902C1.54021 4.30782 0.75 6.19144 0.75 8.15551C0.75 10.1196 1.54021 12.0032 2.94678 13.392C4.35336 14.7808 6.26108 15.561 8.25028 15.561C10.2395 15.561 12.1472 14.7808 13.5538 13.392Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <div class="dark-light-mode">
                                <label for="switch" class="toggle dark-light-switcher">
                                    <input type="checkbox" class="input" id="switch">
                                    <span class="icon icon--moon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                            <path d="M7.51166 0C6.33857 1.09609 5.6053 2.65694 5.6053 4.38904C5.6053 7.70586 8.29416 10.3947 11.611 10.3947C13.3431 10.3947 14.9039 9.66146 16 8.48836C15.7439 12.6798 12.2635 16 8.00757 16C3.58511 16 0 12.4149 0 7.99245C0 3.73655 3.32017 0.256105 7.51166 0Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <span class="icon icon--sun">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                                            <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z">
                                            </path>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                            <button class="at-menu-bar at-header-sidebar-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M1 2C1 1.73478 1.10536 1.48043 1.29289 1.29289C1.48043 1.10536 1.73478 1 2 1H6C6.26522 1 6.51957 1.10536 6.70711 1.29289C6.89464 1.48043 7 1.73478 7 2V6C7 6.26522 6.89464 6.51957 6.70711 6.70711C6.51957 6.89464 6.26522 7 6 7H2C1.73478 7 1.48043 6.89464 1.29289 6.70711C1.10536 6.51957 1 6.26522 1 6V2ZM11 2C11 1.73478 11.1054 1.48043 11.2929 1.29289C11.4804 1.10536 11.7348 1 12 1H16C16.2652 1 16.5196 1.10536 16.7071 1.29289C16.8946 1.48043 17 1.73478 17 2V6C17 6.26522 16.8946 6.51957 16.7071 6.70711C16.5196 6.89464 16.2652 7 16 7H12C11.7348 7 11.4804 6.89464 11.2929 6.70711C11.1054 6.51957 11 6.26522 11 6V2ZM1 12C1 11.7348 1.10536 11.4804 1.29289 11.2929C1.48043 11.1054 1.73478 11 2 11H6C6.26522 11 6.51957 11.1054 6.70711 11.2929C6.89464 11.4804 7 11.7348 7 12V16C7 16.2652 6.89464 16.5196 6.70711 16.7071C6.51957 16.8946 6.26522 17 6 17H2C1.73478 17 1.48043 16.8946 1.29289 16.7071C1.10536 16.5196 1 16.2652 1 16V12ZM11 12C11 11.7348 11.1054 11.4804 11.2929 11.2929C11.4804 11.1054 11.7348 11 12 11H16C16.2652 11 16.5196 11.1054 16.7071 11.2929C16.8946 11.4804 17 11.7348 17 12V16C17 16.2652 16.8946 16.5196 16.7071 16.7071C16.5196 16.8946 16.2652 17 16 17H12C11.7348 17 11.4804 16.8946 11.2929 16.7071C11.1054 16.5196 11 16.2652 11 16V12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header area end -->
