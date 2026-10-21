@php
    // Replis pour les actualités sans couverture : les visuels du gabarit
    // (.webp) ne sont pas dans le dépôt.
    $replis = ['img-11.jpg', 'img-12.jpg', 'img-13.jpg', 'img-14.jpg'];
@endphp
                <!-- blog-details section 1 -->
                <div class="sec-1-blog-details !overflow-hidden pt-150 pb-100">
                    <div class="container">
                        <div class="!items-center flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-2/3 !mx-auto">
                                <div class="nav-menu !flex !items-center !gap-2 !pb-2">
                                    <a href="{{ route('posts.index') }}" class="nav-menu__item neutral-900">Actualités</a>
                                    <span class="nav-menu__item-separator">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="6" height="11" viewBox="0 0 6 11" fill="none">
                                            <path d="M0.666992 0.666672L5.33366 5.33334L0.666992 10" stroke="#585959" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <span class="nav-menu__item neutral-500">{{ $post->category?->name ?? 'Actualité' }}</span>
                                </div>
                                <h2 class="fw-600 !leading-none !mb-0">{{ $post->title }}</h2>
                                <div class="!flex !flex-col md:!flex-row md:!items-end !gap-2 !justify-between pt-30">
                                    <div class="!flex !items-center !gap-2">
                                        <time class="nav-menu__item fz-font-sm neutral-500" datetime="{{ $post->published_at?->toDateString() }}">Publié le {{ $post->published_at?->translatedFormat('d F Y') }}</time>
                                    </div>
                                    <div class="!flex !items-center !gap-6">
                                        <span class="nav-menu__item fz-font-label fw-600 neutral-500">PARTAGER CET ARTICLE</span>
                                        <ul class="at-social-list !list-none !pl-0 !flex !flex-wrap !items-end md:!gap-6 !gap-4">
                                            {{--
                                                Chaque bouton portait le logo d'un réseau et le
                                                libellé d'un autre : l'icône X menait sur Facebook,
                                                annoncée Twitter. Seuls restent les partages qui
                                                existent vraiment. Instagram et TikTok n'offrent
                                                aucune adresse de partage web : le bouton
                                                « copier le lien » les remplace.
                                            --}}
                                            <li>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="at-social__link !flex !items-center !gap-2"
                                                   aria-label="Partager sur Facebook">
                                                    <x-site.reseau-icone nom="facebook" />
                                                </a>
                                            </li>

                                            <li>
                                                <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="at-social__link !flex !items-center !gap-2"
                                                   aria-label="Partager sur WhatsApp">
                                                    <x-site.reseau-icone nom="whatsapp" />
                                                </a>
                                            </li>

                                            <li>
                                                <button type="button"
                                                        class="at-social__link !flex !items-center !gap-2"
                                                        data-copier-lien="{{ url()->current() }}"
                                                        aria-label="Copier le lien de l’article">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7" />
                                                        <path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7" />
                                                    </svg>
                                                    <span class="sr-only" data-copier-etat role="status"></span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full !py-12 mb-40 lg:!mb-60 lg:w-2/3 !mx-auto">
                                <img src="{{ $post->coverUrl() ?? asset('assets/imgs/pages/img-212.webp') }}" class="!max-w-full !h-auto" alt="{{ $post->title }}">
                            </div>
                            <div class="w-full lg:w-2/3 !mx-auto">
                                <div class="content">
                                    @if ($post->excerpt)
                                        <h3 class="h6 fz-font-2xl fw-400 mb-60">{{ $post->excerpt }}</h3>
                                    @endif

                                    <div class="post-body fz-font-lg neutral-900">
                                        {{ $post->bodyHtml() }}
                                    </div>

                                    <x-site.gallery :images="$post->images" :label="'Galerie de l’actualité : '.$post->title" />

                                    {{--
                                        Rubriques et navigation restent dans la colonne de
                                        l'article : hors de celle-ci, ce sont des éléments
                                        flex sans largeur, qui se collent au bord gauche du
                                        conteneur au lieu de s'aligner sur le texte.
                                    --}}
                                    <div class="!flex !flex-wrap !items-center !justify-center !gap-2 pt-40">
                                        @foreach ($categories as $categorie)
                                            <a href="{{ route('posts.index', ['categorie' => $categorie->slug]) }}" class="at-btn filter-btn btn-sm">{{ $categorie->name }}</a>
                                        @endforeach
                                    </div>

                                    <div class="flex flex-wrap -mx-3 [&>*]:px-3 pt-40">
                                        <div class="w-full md:w-5/12 !mr-auto !flex !flex-col !gap-2">
                                            @if ($previous)
                                            <a href="{{ route('posts.show', $previous) }}" aria-label="Actualité précédente">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="13" viewBox="0 0 14 13" fill="none">
                                                    <path d="M3.19036 5.64852H13.3333V7.31518H3.19036L7.66033 11.7851L6.48183 12.9636L0 6.48185L6.48183 0L7.66033 1.17851L3.19036 5.64852Z" fill="currentColor" />
                                                </svg>
                                                <span class="!uppercase">Préc.</span>
                                            </a>
                                            <h3 class="h6 fw-600">
                                                <a href="{{ route('posts.show', $previous) }}">{{ $previous->title }}</a>
                                            </h3>
                                            @endif
                                        </div>
                                        <div class="w-full md:w-5/12 !ml-auto !flex !flex-col !gap-2 !text-right">
                                            @if ($next)
                                            <a href="{{ route('posts.show', $next) }}" aria-label="Actualité suivante"><span class="!uppercase">Suiv.</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                    <path d="M12.5 6.5L17.2143 11L12.5 15.5" stroke="currentColor" stroke-width="1.28571" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M16.9999 11H4.78564" stroke="currentColor" stroke-width="1.28571" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                            <h3 class="h6 fw-600">
                                                <a href="{{ route('posts.show', $next) }}">{{ $next->title }}</a>
                                            </h3>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- blog-details section 2 -->
                <div class="sec-2-blog-details !overflow-hidden pt-100 pb-100 bg-neutral-50">
                    <div class="container">
                        <div class="!items-center flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-1/6">
                                <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                                    <span class="!uppercase">
                                        <span class="text-1">Dernières actualités</span>
                                        <span class="text-2">Dernières actualités</span>
                                    </span>
                                    <i>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                        </svg>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                        </svg>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <div class="pt-20 !items-stretch flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            <!-- beautify ignore:start -->
            @forelse ($related as $autre)
                                <x-site.blog-card :post="$autre"
                                    :fallback="asset('assets/imgs/pages/'.$replis[$loop->index % count($replis)])" />
                            @empty
                                <p class="w-full">C’est pour l’instant la seule actualité publiée.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
