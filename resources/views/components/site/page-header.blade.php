@props([
    'page',
    // Surcharges facultatives, pour une page qui partage le bandeau d'une
    // autre sans en reprendre les textes : une filière par cycle, par exemple.
    'eyebrow' => null,
    'title' => null,
    'titleAccent' => null,
    'intro' => null,
    'tags' => null,
    'crumbLabel' => null,
    'crumbParent' => null,
    'crumbParentUrl' => null,
])

@php
    $header = \App\Models\PageHeader::forKey($page);

    $visuel = $header?->imageUrl();

    $surtitre = $eyebrow ?? $header?->eyebrow;
    $titre = $title ?? $header?->title;
    $accent = $title !== null ? $titleAccent : ($titleAccent ?? $header?->title_accent);
    $accroche = $intro ?? $header?->intro;
    $etiquettes = $tags ?? ($header?->tagList() ?? []);
    $fil = $crumbLabel ?? $header?->label;
@endphp

@if ($header)
    {{--
        En-tête commun à toutes les pages internes, repris du gabarit
        services-details-3 : visuel plein cadre voilé, fil d'Ariane, surtitre,
        titre sur deux lignes, accroche et bouton. Tous les textes, le visuel
        et le bouton se règlent depuis le back-office.

        Sans visuel, `.sd3-hero__stage` retombe sur son fond sombre : le bloc
        reste lisible, il n'y a rien à prévoir de plus.
    --}}
    <section class="sd3-hero !overflow-hidden" data-sd3-hero>
        <div class="sd3-hero__stage">
            @if ($visuel)
                <div class="sd3-hero__media" data-sd3-hero-media>
                    <img src="{{ $visuel }}" alt="" width="1600" height="1000" loading="eager" decoding="async">
                    <div class="sd3-hero__veil" aria-hidden="true"></div>
                </div>
            @endif

            <div class="container p-relative !z-10">
                <div class="sd3-hero__chrome">
                    <nav class="sd3-crumb at_fade_anim" data-fade-from="bottom" aria-label="Fil d’Ariane">
                        <a href="{{ route('home') }}">Accueil</a>
                        <span aria-hidden="true">/</span>

                        @if (filled($crumbParent))
                            <a href="{{ $crumbParentUrl }}">{{ $crumbParent }}</a>
                            <span aria-hidden="true">/</span>
                        @endif

                        <span aria-current="page">{{ $fil }}</span>
                    </nav>
                </div>

                <div class="sd3-hero__core">
                    @if (filled($surtitre))
                        <p class="sd3-hero__kicker at_fade_anim" data-fade-from="bottom" data-delay=".1">{{ $surtitre }}</p>
                    @endif

                    <h1 class="sd3-hero__title">
                        <span class="sd3-hero__line" data-sd3-title-line>{{ $titre }}</span>
                        @if (filled($accent))
                            <span class="sd3-hero__line sd3-hero__line--accent" data-sd3-title-line>{{ $accent }}</span>
                        @endif
                    </h1>

                    @if (filled($accroche) || filled($header->cta_label))
                        <div class="sd3-hero__bar">
                            @if (filled($accroche))
                                <p class="sd3-hero__lead at_fade_anim" data-fade-from="bottom" data-delay=".2">{{ $accroche }}</p>
                            @endif

                            @if (filled($header->cta_label))
                                <div class="sd3-hero__actions at_fade_anim" data-fade-from="bottom" data-delay=".28">
                                    <a class="at-btn !rounded-none" href="{{ $header->cta_url ?: route('contact') }}">
                                        <span><span class="text-1">{{ $header->cta_label }}</span><span class="text-2">{{ $header->cta_label }}</span></span>
                                        <i>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                        </i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @if ($etiquettes !== [])
                    <ul class="sd3-hero__tags" data-sd3-tags aria-label="Points clés">
                        @foreach ($etiquettes as $etiquette)
                            <li data-sd3-tag>{{ $etiquette }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
@endif
