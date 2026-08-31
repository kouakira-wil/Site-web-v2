@php
    $s = \App\Models\PageHeader::forKey('home.services');
@endphp

<!-- at-service-area-start -->
                <div class="at-service-area at-panel-pin-area pt-120 pb-120">
                    <div class="container">
                        <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full">
                                <div class="at-service-subtitle-wrap at-about-border !flex !justify-between !gap-4 mb-50">
                                    <span class="at-btn common-black !uppercase !bg-transparent mb-10 !rounded-none !p-0">
                                        <span class="!uppercase">
                                            <span class="text-1">{{ $s?->eyebrow }}</span>
                                            <span class="text-2">{{ $s?->eyebrow }}</span>
                                        </span>
                                        <i>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                        </i>
                                    </span>
                                    <span class="fs-font-md fw-500 !underline">{{ $s?->intro }}</span>
                                </div>
                            </div>
                            <div class="w-full 2xl:w-1/3 lg:w-1/3 xl:w-1/3 col-xl-4 mb-40">
                                <div class="at-service-content mr-60 mt-20">
                                    <div class="at-service-sales-wrap at-panel-pin fix p-relative">
                                        <div class="at-service-img-wrapper image-container">
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-5.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-6.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-7.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-8.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-9.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                            <div class="hover-image">
                                                <img class="thumb" src="{{ asset('assets/imgs/pages/img-10.webp') }}" alt="Étudiants de l’ESCAT en atelier">
                                            </div>
                                        </div>
                                        <h4 class="h5 fw-600 !mb-0 mt-10"><span class="odometer" data-count="4"></span>+</h4>
                                        <span class="fz-font-lg neutral-500 fw-500">Filières principales</span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full 2xl:w-7/12 !ml-auto lg:w-2/3 xl:w-2/3 mb-40">
                                <div class="at-service-list-wrap">
                                    <a href="{{ route('projects.index') }}">
                                        <div class="at-service-item service-item">
                                            <div class="count">
                                                <span class="number">[01]</span>
                                            </div>

                                            <div class="content">
                                                <h4 class="title">CAP</h4>

                                                <p class="text">
                                                    Une formation courte et pratique pour <br> accéder rapidement au marché du travail. </p>
                                            </div>

                                            <div class="thumb anim-zoomin">
                                                <img src="{{ asset('assets/imgs/pages/img-5-sm.webp') }}" alt="ESCAT">
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('projects.index') }}">
                                        <div class="at-service-item service-item">
                                            <div class="count">
                                                <span class="number">[02]</span>
                                            </div>

                                            <div class="content">
                                                <h4 class="title">Cycle BTS (BAC +2)</h4>

                                                <p class="text">
                                                    Deux ans pour acquérir des compétences <br> techniques mobilisables en entreprise. </p>
                                            </div>

                                            <div class="thumb anim-zoomin">
                                                <img src="{{ asset('assets/imgs/pages/img-6-sm.webp') }}" alt="ESCAT">
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('projects.index') }}">
                                        <div class="at-service-item service-item">
                                            <div class="count">
                                                <span class="number">[03]</span>
                                            </div>

                                            <div class="content">
                                                <h4 class="title">Cycle Licence (BAC +3)</h4>

                                                <p class="text">
                                                    Trois ans pour approfondir un domaine <br> et consolider un projet professionnel. </p>
                                            </div>

                                            <div class="thumb anim-zoomin">
                                                <img src="{{ asset('assets/imgs/pages/img-7-sm.webp') }}" alt="ESCAT">
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('projects.index') }}">
                                        <div class="at-service-item service-item">
                                            <div class="count">
                                                <span class="number">[04]</span>
                                            </div>

                                            <div class="content">
                                                <h4 class="title">Cycle Master (BAC +5)</h4>

                                                <p class="text">
                                                    Cinq ans pour devenir expert de son <br> domaine et encadrer des équipes. </p>
                                            </div>

                                            <div class="thumb anim-zoomin">
                                                <img src="{{ asset('assets/imgs/pages/img-8-sm.webp') }}" alt="ESCAT">
                                            </div>
                                        </div>
                                    </a>
                                    <div class="at-service-btn pt-30">
                                        <a class="at-btn" href="{{ route('contact') }}">
                                            <span>
                                                <span class="text-1">Prendre une préinscription</span>
                                                <span class="text-2">Prendre une préinscription</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- at-service-area-end -->
