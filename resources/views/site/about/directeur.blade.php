@php
    $mot = \App\Models\PageHeader::forKey('ecole.directeur');
    $paragraphes = $mot?->bodyParagraphs() ?? [];
@endphp

@if ($mot && (filled($mot->intro) || $paragraphes !== []))
    {{--
        Mot du directeur : photo pleine largeur, carte blanche posée par-dessus.
        La carte reste opaque pour que le texte demeure lisible sur la photo.

        Le dépliage réutilise le mécanisme `data-at-collapse` du gabarit (celui
        de la FAQ) : aucun script supplémentaire. Le libellé du bouton alterne
        en CSS selon `aria-expanded`, que ce mécanisme met à jour.
    --}}
    <section class="directeur-area" aria-labelledby="titre-directeur">
        <div class="directeur-scene @if ($mot->imageUrl()) directeur-scene--photo @endif">
            @if ($mot->imageUrl())
                <img class="directeur-scene__fond" src="{{ $mot->imageUrl() }}" alt=""
                     width="1800" height="900" loading="lazy" decoding="async">
            @endif

            <div class="container directeur-scene__contenu">
                <div class="directeur-card">
                <h2 id="titre-directeur" class="directeur-card__title">{{ $mot->title }}</h2>

                @if (filled($mot->intro))
                    <p class="directeur-card__lead">{{ $mot->intro }}</p>
                @endif

                @if ($paragraphes !== [])
                    <div id="mot-directeur-suite" class="directeur-card__suite at-collapse">
                        <div class="directeur-card__suite-inner">
                            @foreach ($paragraphes as $paragraphe)
                                <p class="directeur-card__text">{{ $paragraphe }}</p>
                            @endforeach
                        </div>
                    </div>

                    <button type="button"
                            class="directeur-card__toggle"
                            data-at-collapse
                            data-at-collapse-target="#mot-directeur-suite"
                            aria-expanded="false"
                            aria-controls="mot-directeur-suite">
                        <span class="directeur-card__toggle-plus">Voir plus</span>
                        <span class="directeur-card__toggle-moins">Voir moins</span>
                    </button>
                @endif
                </div>
            </div>
        </div>
    </section>
@endif
