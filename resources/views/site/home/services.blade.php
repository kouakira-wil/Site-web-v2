@php
    $s = \App\Models\PageHeader::forKey('home.services');

    // Les cartes de cycles, dans l'ordre : home.services.1 à .3.
    $cycles = \App\Models\PageHeader::query()
        ->where('key', 'like', 'home.services.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($cycle): bool => filled($cycle->title))
        ->values();

    /*
     * Compteur sous la pile de photos, au format « valeur | libellé ». Il
     * tient sur une seule ligne ; les suivantes seraient sans emplacement.
     */
    $compteur = array_map('trim', explode('|', $s?->tagList()[0] ?? '', 2));
    $compteurValeur = $compteur[0] ?? '';
    $compteurLibelle = $compteur[1] ?? '';
@endphp

@if ($s)
<!-- at-service-area-start -->
<div class="at-service-area at-panel-pin-area pt-120 pb-120">
<div class="container">
    <div class="flex flex-wrap -mx-3 [&>*]:px-3">
        <div class="w-full">
            <div class="at-service-subtitle-wrap at-about-border !flex !justify-between !gap-4 mb-50">
                <span class="at-btn common-black !uppercase !bg-transparent mb-10 !rounded-none !p-0">
                    <span class="!uppercase">
                        <span class="text-1">{{ $s->eyebrow }}</span>
                        <span class="text-2">{{ $s->eyebrow }}</span>
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

                @if (filled($s->intro))
                    <span class="fs-font-md fw-500 !underline">{{ $s->intro }}</span>
                @endif
            </div>
        </div>
        <div class="w-full 2xl:w-1/3 lg:w-1/3 xl:w-1/3 col-xl-4 mb-40">
            <div class="at-service-content mr-60 mt-20">
                <div class="at-service-sales-wrap at-panel-pin fix p-relative">
                    {{--
                        Le script du gabarit apparie les cartes et les vignettes par
                        leur rang : survoler la carte n° 2 révèle la deuxième image de
                        cette pile. Une entrée par carte, dans le même ordre, donc,
                        et le conteneur reste en place même sans photo — le sauter
                        décalerait tous les rangs suivants.
                    --}}
                    <div class="at-service-img-wrapper image-container">
                        @foreach ($cycles as $cycle)
                            <div class="hover-image">
                                @if ($cycle->imageUrl())
                                    <img class="thumb" src="{{ $cycle->imageUrl() }}" alt="{{ $cycle->title }}">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if (filled($compteurValeur))
                        {{-- Le compteur s'anime depuis zéro : la valeur est lue par
                             `data-count`, le signe reste en dehors du mécanisme. --}}
                        <h4 class="h5 fw-600 !mb-0 mt-10"><span class="odometer" data-count="{{ (int) $compteurValeur }}"></span>+</h4>
                        <span class="fz-font-lg neutral-500 fw-500">{{ $compteurLibelle }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="w-full 2xl:w-7/12 !ml-auto lg:w-2/3 xl:w-2/3 mb-40">
            <div class="at-service-list-wrap">
                @foreach ($cycles as $cycle)
                    <a href="{{ $cycle->cta_url ?: route('projects.index') }}">
                        <div class="at-service-item service-item">
                            <div class="count">
                                {{-- Numérotation calculée : réordonner les blocs
                                     au back-office suffit à la renuméroter. --}}
                                <span class="number">[{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}]</span>
                            </div>

                            <div class="content">
                                <h4 class="title">{{ $cycle->title }}</h4>

                                @if (filled($cycle->intro))
                                    <p class="text">{{ $cycle->intro }}</p>
                                @endif
                            </div>

                            <div class="thumb anim-zoomin">
                                @if ($cycle->imageUrl())
                                    <img src="{{ $cycle->imageUrl() }}" alt="{{ $cycle->title }}">
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach

                @if (filled($s->cta_label))
                <div class="at-service-btn pt-30">
                    <a class="at-btn" href="{{ $s->cta_url ?: route('contact') }}">
                        <span>
                            <span class="text-1">{{ $s->cta_label }}</span>
                            <span class="text-2">{{ $s->cta_label }}</span>
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
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<!-- at-service-area-end -->
@endif
