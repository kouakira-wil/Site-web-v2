@php
    $parcours = \App\Models\PageHeader::forKey('ecole.parcours');
    $pole = \App\Models\PageHeader::forKey('ecole.parcours.pole');

    // Les étapes du cursus, dans l'ordre : ecole.parcours.1 à .4. La carte des
    // pôles partage le préfixe mais tient sa propre colonne.
    $etapes = \App\Models\PageHeader::query()
        ->where('key', 'like', 'ecole.parcours.%')
        ->where('key', '!=', 'ecole.parcours.pole')
        ->orderBy('position')
        ->get()
        ->filter(fn ($etape): bool => filled($etape->title))
        ->values();
@endphp

@if ($parcours)
<!-- About section 2 -->
                <div class="sec-2-about block-journey pt-120 pb-120 bg-neutral-900 changeless">
                    <div class="container">
                        <div class="!items-end pb-60 flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-7/12 md:w-7/12">
                                @if (filled($parcours->eyebrow))
                                <span class="at-btn !text-white !bg-transparent mb-10 !rounded-none !p-0">
                                    <span class="!uppercase !text-white">
                                        <span class="text-1">{{ $parcours->eyebrow }}</span>
                                        <span class="text-2">{{ $parcours->eyebrow }}</span>
                                    </span>
                                    <i>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="#fff"></path>
                                        </svg>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="#fff"></path>
                                        </svg>
                                    </i>
                                </span>
                                @endif

                                <h3 class="!text-white reveal-text">{{ $parcours->title }}</h3>

                                @if (filled($parcours->intro))
                                    <p class="!text-white fz-font-lg">{{ $parcours->intro }}</p>
                                @endif
                            </div>

                            @if (filled($parcours->cta_label))
                            @php $lienBouton = $parcours->cta_url ?: route('contact'); @endphp
                            <div class="w-full lg:w-1/4 md:w-5/12 !ml-auto !flex md:!justify-end">
                                <div class="at-btn-group at_fade_anim" data-delay=".4" data-fade-from="bottom" data-ease="bounce">
                                    <a class="at-btn-circle" href="{{ $lienBouton }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                            <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <a class="at-btn z-index-1" href="{{ $lienBouton }}">{{ $parcours->cta_label }}</a>
                                    <a class="at-btn-circle" href="{{ $lienBouton }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                            <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                            @if ($pole && filled($pole->title))
                            <div class="w-full lg:w-1/3">
                                <div class="alt-portfolio-item mb-30 at-hover-item">
                                    <a href="{{ $pole->cta_url ?: route('projects.index') }}" class="alt-portfolio-thumb mb-15 p-relative fix !block">
                                        <img class="!w-full scale-img-from-to" data-value-1="1.5" data-value-2="1"
                                             src="{{ $pole->imageUrl() ?? asset('assets/imgs/pages/img-3.jpeg') }}" alt="ESCAT">
                                        <div class="alt-portfolio-btn">
                                            <div class="content">
                                                @if (filled($pole->eyebrow))
                                                    <span class="!bg-transparent !uppercase !border !px-4 !py-1 !rounded-full !text-white fz-font-label">{{ $pole->eyebrow }}</span>
                                                @endif

                                                <h2 class="fw-400 fz-font-3xl !text-white !mb-0 mt-20">{{ $pole->title }}</h2>

                                                @if (filled($pole->intro))
                                                    <p class="!text-white fz-font-md !mb-0 mt-10 text-truncate-2 des">{{ $pole->intro }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if ($etapes->isNotEmpty())
                            <div class="w-full lg:w-7/12 !ml-auto pt-80">
                                <!-- My Journey list -->
                                <ul class="journey-list">
                                    @foreach ($etapes as $etape)
                                    <li class="journey-list__item scroll-move-up">
                                        @if (filled($etape->eyebrow))
                                            <span class="journey-list__date">{{ $etape->eyebrow }}</span>
                                        @endif

                                        <div class="journey-list__body">
                                            <h3 class="h6 journey-list__title neutral-0">{{ $etape->title }}</h3>

                                            @if (filled($etape->intro))
                                                <p class="journey-list__desc">{{ $etape->intro }}</p>
                                            @endif
                                        </div>

                                        {{-- « Voir » seul ne dit pas quoi : l'intitulé de l'étape
                                             complète le libellé pour les lecteurs d'écran. --}}
                                        <a href="{{ $etape->cta_url ?: route('projects.index') }}" class="journey-list__link"
                                           aria-label="En savoir plus : {{ $etape->title }}">
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
@endif
