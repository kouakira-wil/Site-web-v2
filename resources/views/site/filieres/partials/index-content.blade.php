
                <!-- portfolio-1 section 1 -->
                <div class="sec-1-portfolio-1 !overflow-hidden pt-150 pb-110 border-bottom-100">
                    <div class="container pb-60">
                        <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full 2xl:w-2/3 lg:w-7/12">
                            </div>
                            <div class="w-full 2xl:w-1/4 lg:w-5/12 lg:!ml-auto">
                                <p class="fz-font-lg neutral-900 lg:!text-right">Des parcours construits avec les entreprises, du CAP au Master.</p>
                            </div>
                        </div>
                    </div>
                    <div class="container !relative">
                        <div class="!items-center flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-2/3">
                                <div class="filter-portfolio !flex !flex-wrap !items-center !gap-2">
                                    <a href="{{ route('projects.index') }}" class="at-btn filter-btn btn-sm{{ $activeSlug === '' ? ' active' : '' }}">Toutes les filières</a>
                                    @foreach ($categories as $categorie)
                                        <a href="{{ route('projects.index', ['categorie' => $categorie->slug]) }}"
                                           class="at-btn filter-btn btn-sm{{ $activeSlug === $categorie->slug ? ' active' : '' }}">{{ $categorie->name }}</a>
                                    @endforeach
                                    </div>
                            </div>
                            <div class="w-full lg:w-1/6 !ml-auto !text-right lg:!mt-0 !mt-4">
                                <div class="!flex !justify-end">
                                    <select class="at-select">
                                        <option value="1">Plus récentes</option>
                                        <option value="2">Plus anciennes</option>
                                        <option value="3">Les plus demandées</option>
                                    </select>
                                </div>
                            </div>
                            <div class="w-full pb-30"></div>
                        </div>
                        <h2 class="!absolute !p-0 !m-0 !overflow-hidden" style="width:1px;height:1px;clip:rect(0,0,0,0);white-space:nowrap;border:0;">Filières ouvertes</h2>
                        <div class="!justify-center flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            <!-- beautify ignore:start -->
            @forelse ($projects as $filiere)
                <x-site.filiere-item :filiere="$filiere"
                    class="w-full 2xl:w-1/2 lg:w-7/12"
                    :fallback="asset('assets/imgs/pages/img-'.(11 + ($loop->index % 6)).'.webp')" />
            @empty
                <p class="py-40">Aucune filière ne correspond à ce filtre.</p>
            @endforelse

            <div class="w-full pt-40">{{ $projects->links() }}</div>

            <!-- beautify ignore:end -->
                            <div class="w-full !text-center">
                                <a class="at-btn" href="{{ route('projects.index') }}">
                                    <span>
                                        <span class="text-1">VOIR PLUS DE FILIÈRES</span>
                                        <span class="text-2">VOIR PLUS DE FILIÈRES</span>
                                    </span>
                                    <i>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                        </svg>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                        </svg>
                                    </i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- About section 7 -->
                <div class="sec-7-about !py-12 !overflow-hidden">
                    <div class="container !py-12">
                        <div class="flex flex-wrap -mx-6 [&>*]:px-6 gap-y-12">
                            <div class="w-full lg:w-1/3">
                                <span class="at-btn common-black !uppercase !bg-transparent mb-10 !rounded-none !p-0">
                                    <span class="!uppercase">
                                        <span class="text-1">FAQ</span>
                                        <span class="text-2">FAQ</span>
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
                                <h3 class="section-title !leading-none reveal-text">Questions <br> fréquentes</h3>
                                <h4 class="h6 fz-font-lg fw-500">
                                    Admission, dossier et scolarité : <br class="!hidden 2xl:!block"> les réponses aux questions les plus posées.
                                </h4>
                            </div>
                            <div class="w-full lg:w-7/12 lg:!ml-auto">
                                <div class="pt-80" id="accordionExample">
                                    <div class="at-faq-item bg-neutral-0 border-100 scroll-move-up !rounded-2xl">
                                        <div class="at-faq-header !flex !gap-2">
                                            <div class="box-number">
                                                <span class="at-faq-number">1</span>
                                            </div>
                                            <button class="at-faq-button" type="button" data-at-collapse data-at-collapse-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Comment soumettre un dossier ?</button>
                                        </div>
                                        <div id="collapseOne" class="at-faq-collapse at-collapse is-open" data-at-collapse-parent="#accordionExample">
                                            <div class="at-faq-body">
                                                <p>Le dossier se dépose au secrétariat ou s’envoie à infos@escat.org : pièce d’identité, relevés de notes et dernier diplôme obtenu.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="at-faq-item bg-neutral-0 border-100 scroll-move-up !rounded-2xl">
                                        <div class="at-faq-header !flex !gap-2">
                                            <div class="box-number">
                                                <span class="at-faq-number">2</span>
                                            </div>
                                            <button class="at-faq-button collapsed" type="button" data-at-collapse data-at-collapse-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Quels sont les délais d’inscription ?</button>
                                        </div>
                                        <div id="collapseTwo" class="at-faq-collapse at-collapse" data-at-collapse-parent="#accordionExample">
                                            <div class="at-faq-body">
                                                <p>Les inscriptions ouvrent avant chaque rentrée et se poursuivent jusqu’au remplissage des effectifs.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="at-faq-item bg-neutral-0 border-100 scroll-move-up !rounded-2xl">
                                        <div class="at-faq-header !flex !gap-2">
                                            <div class="box-number">
                                                <span class="at-faq-number">3</span>
                                            </div>
                                            <button class="at-faq-button collapsed" type="button" data-at-collapse data-at-collapse-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Peut-on régler la scolarité en plusieurs fois ?</button>
                                        </div>
                                        <div id="collapseThree" class="at-faq-collapse at-collapse" data-at-collapse-parent="#accordionExample">
                                            <div class="at-faq-body">
                                                <p>Oui, un échelonnement est possible ; les modalités se fixent avec le service comptable à l’inscription.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="at-faq-item bg-neutral-0 border-100 scroll-move-up !rounded-2xl">
                                        <div class="at-faq-header !flex !gap-2">
                                            <div class="box-number">
                                                <span class="at-faq-number">4</span>
                                            </div>
                                            <button class="at-faq-button collapsed" type="button" data-at-collapse data-at-collapse-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Peut-on passer du BTS à la Licence ?</button>
                                        </div>
                                        <div id="collapseFour" class="at-faq-collapse at-collapse" data-at-collapse-parent="#accordionExample">
                                            <div class="at-faq-body">
                                                <p>Oui : le cursus est conçu comme un continuum, du CAP au Master, dans la même filière.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
