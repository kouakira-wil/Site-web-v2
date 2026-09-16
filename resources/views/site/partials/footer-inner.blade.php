{{-- Réserve la hauteur de défilement occupée par le pied de page fixe. --}}
<div class="footer-placeholder"></div>

<footer class="footer-fixed-bottom bg-neutral-950 changeless">
            <div class="at-footer-area mp-footer-style mp-footer-style-2 pt-120 !pb-0">
                <div class="container">
                    <!-- Top section: Logo + Contact | Nav | Follow Us + Social -->
                    <div class="md:!pb-12 !pb-2 flex flex-wrap -mx-6 [&>*]:px-6 gap-y-12">
                        <div class="w-full lg:w-1/3">
                            <div class="!flex !flex-wrap !items-start !gap-12">
                                <div class="at-header-logo">
                                    <a href="{{ route('home') }}">
                                        <img data-width="30" src="{{ asset('assets/imgs/template/logo/favicon-dark.png') }}" alt="ESCAT">
                                        <p class="h6 fw-700 !text-white !mb-0 fz-24">ESCAT</p>
                                    </a>
                                </div>
                                <div class="!flex !flex-col !gap-4">
                                    <p class="h6 !text-white !mb-2 !font-medium">+242 06 884 83 44</p>
                                    <p class="h6 !text-white !mb-2">
                                        <a href="mailto:infos@escat.org" class="!text-white !no-underline">infos@escat.org</a>
                                    </p>
                                    <p class="h6 !text-white !mb-0">
                                        
                                        Brazzaville, République du Congo
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/3 md:w-1/2 !mx-auto">
                            <div class="at-footer-widget alt-footer-link-item-wrap flex flex-wrap -mx-3 [&>*]:px-3">
                                <div class="alt-footer-link-item w-1/2">
                                    <ul>
                                        <li class="mb-15"><a href="{{ route('home') }}">Accueil</a></li>
                                        <li class="mb-15"><a href="{{ route('about') }}\">L’école</a></li>
                                        <li class="mb-15"><a href="{{ route('projects.index') }}">Filières</a></li>
                                        <li class="mb-15"><a href="{{ route('posts.index') }}">Actualités</a></li>
                                        <li class="mb-15"><a href="{{ route('contact') }}">Contact</a></li>
                                    </ul>
                                </div>
                                <div class="alt-footer-link-item w-1/2">
                                    <ul>
                                        <li class="mb-15"><a href="{{ route('projects.index') }}">Cycle CAP</a></li>
                                        <li class="mb-15"><a href="{{ route('projects.index') }}">Cycle BTS (BAC +2)</a></li>
                                        <li class="mb-15"><a href="{{ route('projects.index') }}">Cycle Licence (BAC +3)</a></li>
                                        <li class="mb-15"><a href="{{ route('projects.index') }}">Cycle Master (BAC +5)</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/4 md:w-1/2 !flex-col lg:!justify-end !hidden md:!flex">
                            <p class="footer-2-follow-label !text-white !opacity-50 !uppercase small !mb-4">Nous suivre</p>
                            <div class="at-footer-widget at-footer-link">
                                <div class="at-hero-social">
                                    <a href="{{ config('social.facebook') ?: route('contact') }}" target="_blank" rel="noopener noreferrer">
                                        Facebook
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none">
                                            <path d="M5.62494 9.99994L0.562517 10L0.5625 8.75003L4.49994 8.74996L4.5 2.39273L2.27828 4.86124L1.48278 3.97739L5.0625 0L8.64225 3.97739L7.84676 4.86124L5.625 2.3927L5.62494 9.99994Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <a href="{{ config('social.instagram') ?: route('contact') }}" target="_blank" rel="noopener noreferrer">
                                        Instagram
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none">
                                            <path d="M5.62494 9.99994L0.562517 10L0.5625 8.75003L4.49994 8.74996L4.5 2.39273L2.27828 4.86124L1.48278 3.97739L5.0625 0L8.64225 3.97739L7.84676 4.86124L5.625 2.3927L5.62494 9.99994Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <a href="{{ config('social.tiktok') ?: route('contact') }}" target="_blank" rel="noopener noreferrer">
                                        TikTok
                                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="10" viewBox="0 0 9 10" fill="none">
                                            <path d="M5.62494 9.99994L0.562517 10L0.5625 8.75003L4.49994 8.74996L4.5 2.39273L2.27828 4.86124L1.48278 3.97739L5.0625 0L8.64225 3.97739L7.84676 4.86124L5.625 2.3927L5.62494 9.99994Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="footer-2-border pt-40 pb-40">
                        <!-- Bottom section: Copyright | Restons en contact + Hours | Service tags -->
                        <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            <div class="w-full lg:w-5/6 md:w-2/3">
                                <span class="at-footer-copyright">ESCAT © 2026</span>
                                <div class="at-title-anim !overflow-hidden">
                                    <h2 class="footer-2-connect-title !text-white !mb-0 at-title-text text-scale-anim">Restons en contact</h2>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/6 md:w-1/3 !text-right">
                                <div class="!flex !flex-wrap !items-end !gap-6 md:!gap-12 !mb-4">
                                    <div class="footer-2-hours !text-white">
                                        <span class="!block fz-font-md !opacity-50">Lundi -Samedi </span>
                                        <h3 class="h5 fw-400 common-white">8h - 16h</h3>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="!hidden md:!block flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full">
                                <ul class="!flex !flex-wrap lg:!gap-6 !gap-2 !pl-4 !pt-6 !pb-2">
                                    <li>
                                        <div class="at-btn at-btn-border-white !border-0 !pl-2 !pr-2 !py-0 common-white !opacity-50 !bg-transparent !rounded-none">
                                            <span>
                                                <span class="text-1">Réseaux et Télécommunications</span>
                                                <span class="text-2">Réseaux et Télécommunications</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="at-btn at-btn-border-white !border-0 !pl-2 !pr-2 !py-0 common-white !opacity-50 !bg-transparent !rounded-none">
                                            <span>
                                                <span class="text-1">Génie Informatique</span>
                                                <span class="text-2">Génie Informatique</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="at-btn at-btn-border-white !border-0 !pl-2 !pr-2 !py-0 common-white !opacity-50 !bg-transparent !rounded-none">
                                            <span>
                                                <span class="text-1">Comptabilité et Finance</span>
                                                <span class="text-2">Comptabilité et Finance</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="at-btn at-btn-border-white !border-0 !pl-2 !pr-2 !py-0 common-white !opacity-50 !bg-transparent !rounded-none">
                                            <span>
                                                <span class="text-1">Électromécanique</span>
                                                <span class="text-2">Électromécanique</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
