                <!-- archive-1 section 1 -->
                <div class="sec-1-archive-1 !overflow-hidden pt-150">
                    <div class="container">
                        <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            <div class="w-full lg:w-1/2">
                                <h2 class="h6 fw-500 fz-font-lg !mb-0">Événements, travaux pratiques et moments forts de la vie de l’école, à Brazzaville.</h2>
                            </div>

                            {{-- Filtre par rubrique, dans le bloc du haut. --}}
                            <div class="w-full lg:w-1/2 !ml-auto">
                                {{--
                                    Filtrage sans rechargement : `initPortfolioFilter()`
                                    du gabarit masque et révèle les cartes selon
                                    `data-filter` / `data-category`. Des boutons, et non
                                    des liens : rien ne navigue, il n'y a donc pas de
                                    destination à annoncer.
                                --}}
                                <div class="filter-portfolio !flex !flex-wrap !items-center lg:!justify-end !gap-2"
                                     role="group" aria-label="Filtrer par rubrique">
                                    <button type="button" data-filter="all"
                                            class="at-btn filter-btn btn-sm active"
                                            aria-pressed="true">Toutes</button>

                                    @foreach ($categories as $categorie)
                                        <button type="button" data-filter="{{ $categorie->slug }}"
                                                class="at-btn filter-btn btn-sm"
                                                aria-pressed="false">{{ $categorie->name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- archive-1 section 2 -->
                <div class="sec-2-archive-1 !overflow-hidden pt-100 pb-100">
                    <div class="container">
                        <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                            <!-- beautify ignore:start -->
            @forelse ($posts as $post)
                                <x-site.blog-card
                                    :post="$post"
                                    :fallback="asset('assets/imgs/pages/img-'.(23 + ($loop->index % 6)).'.webp')"
                                />
                            @empty
                                <p class="w-full py-40">Aucune actualité publiée pour le moment.</p>
                            @endforelse
                    </div>
                </div>
                </div>
                <!--Home 3 Section 12-->
                <div class="home-3-section-12 pb-100">
                    <div class="container">
                        <div class="box-newsletter bg-neutral-50 pt-80 pb-100 md:!px-12 !px-6">
                            <div class="flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                                <div class="w-full lg:w-1/6">
                                    <div class="ripple-image ripples !rounded-lg !overflow-hidden !inline-flex">
                                        <img class="img-cover" src="{{ asset('assets/imgs/pages/img-74.webp') }}" alt="ESCAT">
                                    </div>
                                </div>
                                <div class="w-full lg:w-5/12">
                                    <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                                        <span class="!uppercase">
                                            <span class="text-1">lettre d’information</span>
                                            <span class="text-2">lettre d’information</span>
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
                                    <h2>Restez informé</h2>
                                    <p class="fz-font-xl !mb-0">Recevez les dates de rentrée, les journées portes ouvertes et les actualités de l’école.</p>
                                </div>
                                <div class="w-full lg:w-1/3 !ml-auto !self-end">
                                    <div class="input-subscribe p-relative changeless">
                                        <input placeholder="Enter you mail .." type="email" class="bg-neutral-0">
                                        <button class="p-absolute !right-0 !top-1/2 bg-neutral-900 !rounded-lg size-56 !-translate-y-1/2 !mr-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M9.33333 3.33301L14 7.99967M14 7.99967L9.33333 12.6663M14 7.99967H2" stroke="#FEFEFE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="!mb-0 pt-20">En vous inscrivant, vous acceptez <br> de recevoir les communications de l’ESCAT</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
