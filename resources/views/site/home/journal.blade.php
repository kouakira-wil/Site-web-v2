@php
    $s = \App\Models\PageHeader::forKey('home.journal');

    // Replis pour les actualités sans couverture. Les visuels du gabarit
    // (img-23.webp à img-26.webp) ne sont pas dans le dépôt : une actualité
    // sans image y affichait un cadre cassé.
    $replis = ['img-11.jpg', 'img-12.jpg', 'img-13.jpg', 'img-14.jpg'];
@endphp

<!-- at-sec13-area-start -->
                <div class="at-sec13-thumb fix !w-full scale-up-img p-relative pt-120 pb-120">
                    <div class="container">
                        <div class="!items-end mb-50 flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-2/3 2xl:w-1/2">
                                <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
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
                                <h3 class="alt-section-title !leading-none neutral-900 fw-700 mb-30 reveal-text !mb-0">{{ $s?->title }}</h3>
                            </div>
                            <div class="w-full 2xl:w-1/6 lg:w-1/4 !ml-auto 2xl:!text-right lg:!text-right">
                                <div class="at-service-btn pt-30">
                                    <a class="at-btn" href="{{ route('posts.index') }}">
                                        <span>
                                            <span class="text-1">{{ $s?->cta_label }}</span>
                                            <span class="text-2">{{ $s?->cta_label }}</span>
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
                        {{--
                            Les quatre dernières actualités, sur une rangée.
                            `items-stretch` aligne le bas des cartes : sans lui,
                            chacune s'arrêtait à la hauteur de son propre titre.
                        --}}
                        <div class="!items-stretch flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            @forelse ($recentPosts as $post)
                                <x-site.blog-card
                                    :post="$post"
                                    :fallback="asset('assets/imgs/pages/'.$replis[$loop->index % count($replis)])"
                                />
                            @empty
                                <p class="w-full">Aucune actualité publiée pour le moment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- at-sec13-area-end -->
