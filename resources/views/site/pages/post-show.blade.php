
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
                                            <li>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="at-social__link !flex !items-center !gap-2" aria-label="Twitter">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 15" fill="none" aria-hidden="true">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0H5.25L8.98421 5.21053L13.5 0H15.5L9.8895 6.47368L16 15H10.75L7.01579 9.7895L2.5 15H0.5L6.11053 8.52632L0 0ZM11.5204 13.5L2.92043 1.5H4.47957L13.0796 13.5H11.5204Z" fill="currentColor"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="at-social__link !flex !items-center !gap-2" aria-label="Facebook">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                                        <path d="M12.4024 18V11.0344H14.7347L15.0838 8.3265H12.4024V6.59765C12.4024 5.81364 12.62 5.27934 13.7443 5.27934L15.1783 5.27867V2.85676C14.9302 2.82382 14.0791 2.75006 13.0888 2.75006C11.0213 2.75006 9.606 4.01198 9.606 6.32952V8.3265H7.2677V11.0344H9.606V18H1C0.44772 18 0 17.5523 0 17V1C0 0.44772 0.44772 0 1 0H17C17.5523 0 18 0.44772 18 1V17C18 17.5523 17.5523 18 17 18H12.4024Z" fill="currentColor"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="at-social__link !flex !items-center !gap-2" aria-label="Instagram">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                                        <path d="M9.92475 0C10.9376 0.00167406 11.4509 0.00703828 11.8945 0.0202418L12.0692 0.025948C12.2709 0.0331213 12.47 0.0421215 12.7101 0.0533719C13.6678 0.0976265 14.3211 0.249129 14.8949 0.471887C15.4881 0.700648 15.9892 1.00966 16.4894 1.50993C16.9889 2.0102 17.298 2.51271 17.5275 3.10448C17.7494 3.67751 17.901 4.33153 17.946 5.28931C17.9567 5.52933 17.9653 5.72841 17.9725 5.93024L17.9781 6.10492C17.9913 6.5484 17.9973 7.06177 17.9992 8.07471L17.9999 8.74577C18 8.82777 18 8.91237 18 8.99967L17.9999 9.25357L17.9994 9.92472C17.9977 10.9376 17.9923 11.451 17.9791 11.8944L17.9734 12.0691C17.9662 12.271 17.9572 12.4701 17.946 12.7101C17.9017 13.6679 17.7494 14.3211 17.5275 14.8949C17.2987 15.4882 16.9889 15.9892 16.4894 16.4895C15.9892 16.989 15.4859 17.298 14.8949 17.5275C14.3211 17.7495 13.6678 17.901 12.7101 17.946C12.47 17.9567 12.2709 17.9654 12.0692 17.9725L11.8945 17.9781C11.4509 17.9914 10.9376 17.9973 9.92475 17.9993L9.25359 18C9.1716 18 9.087 18 8.99969 18H8.7458L8.07464 17.9994C7.06175 17.9977 6.54839 17.9924 6.1049 17.9791L5.93022 17.9734C5.72839 17.9662 5.5293 17.9572 5.2893 17.946C4.33151 17.9018 3.67899 17.7495 3.10448 17.5275C2.51195 17.2988 2.01018 16.989 1.50992 16.4895C1.00965 15.9892 0.701394 15.4859 0.471886 14.8949C0.249129 14.3211 0.0983735 13.6679 0.053372 12.7101C0.0426796 12.4701 0.0339582 12.271 0.026893 12.0691L0.0212318 11.8944C0.00806435 11.451 0.00206123 10.9376 0.000117163 9.92472L0 8.07471C0.00167406 7.06177 0.00702925 6.5484 0.0202327 6.10492L0.0259479 5.93024C0.0331212 5.72841 0.0421216 5.52933 0.053372 5.28931C0.0976175 4.33078 0.249129 3.67825 0.471886 3.10448C0.700638 2.51196 1.00965 2.0102 1.50992 1.50993C2.01018 1.00966 2.51271 0.701396 3.10448 0.471887C3.67825 0.249129 4.33077 0.0983735 5.2893 0.0533719C5.5293 0.0426885 5.72839 0.0339673 5.93022 0.026902L6.1049 0.0212407C6.54839 0.00806417 7.06175 0.00206102 8.07464 0.000116949L9.92475 0ZM8.99969 4.49953C6.51298 4.49953 4.49952 6.51518 4.49952 8.99967C4.49952 11.4864 6.51517 13.4998 8.99969 13.4998C11.4864 13.4998 13.4998 11.4842 13.4998 8.99967C13.4998 6.513 11.4841 4.49953 8.99969 4.49953ZM8.99969 6.2996C10.491 6.2996 11.6998 7.50805 11.6998 8.99967C11.6998 10.4909 10.4913 11.6998 8.99969 11.6998C7.50843 11.6998 6.29958 10.4914 6.29958 8.99967C6.29958 7.50841 7.50798 6.2996 8.99969 6.2996ZM13.7249 3.14949C13.1045 3.14949 12.5998 3.65342 12.5998 4.27376C12.5998 4.89411 13.1037 5.39882 13.7249 5.39882C14.3452 5.39882 14.8499 4.89489 14.8499 4.27376C14.8499 3.65342 14.3443 3.14871 13.7249 3.14949Z" fill="currentColor"></path>
                                                    </svg>
                                                </a>
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
                        <div class="pt-20 flex flex-wrap -mx-3 [&>*]:px-3">
                            <!-- beautify ignore:start -->
            @forelse ($related as $autre)
                                <x-site.blog-card :post="$autre"
                                    :fallback="asset('assets/imgs/pages/img-'.(23 + ($loop->index % 4)).'.webp')" />
                            @empty
                                <p class="w-full">Pas d’autre actualité dans cette rubrique.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
