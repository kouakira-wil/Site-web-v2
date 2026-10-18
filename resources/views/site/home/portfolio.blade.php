@php
    $s = \App\Models\PageHeader::forKey('home.portfolio');

    // Le texte qui tourne autour du médaillon : une seule ligne, saisie au
    // back-office. Sans elle, le médaillon ne s'affiche pas.
    $pastille = $s?->tagList()[0] ?? null;
@endphp

@if ($s)
    {{--
        Mise en avant des filières : discours à gauche, photo à droite.

        La photo n'est pas décorative au sens strict — elle montre l'école —
        mais elle n'ajoute rien au texte qui la précède : son `alt` reste vide
        pour ne pas faire répéter le titre au lecteur d'écran.
    --}}
    <section class="phare" aria-labelledby="titre-phare">
        <div class="container">
            <div class="phare__grille">
                <div class="phare__corps">
                    @if (filled($s->eyebrow))
                        <p class="phare__eyebrow">
                            @if (filled($s->icon))
                                <x-site.icon :name="$s->icon" class="phare__eyebrow-icone" />
                            @endif
                            {{ $s->eyebrow }}
                        </p>
                    @endif

                    <h2 id="titre-phare" class="phare__titre reveal-text">
                        {{ $s->title }}
                        @if (filled($s->title_accent))
                            <span class="phare__accent">{{ $s->title_accent }}</span>
                        @endif
                    </h2>

                    @if (filled($s->intro))
                        <p class="phare__chapo">{{ $s->intro }}</p>
                    @endif

                    @if (filled($s->body))
                        <blockquote class="phare__citation">{{ $s->body }}</blockquote>
                    @endif

                    @if (filled($s->cta_label))
                        <a class="phare__bouton" href="{{ $s->cta_url ?: route('projects.index') }}">
                            <span class="phare__bouton-texte">{{ $s->cta_label }}</span>
                            <span class="phare__bouton-rond" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 11 11" fill="none">
                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                </svg>
                            </span>
                        </a>
                    @endif
                </div>

                <div class="phare__media">
                    <img class="phare__photo"
                         src="{{ $s->imageUrl() ?? asset('assets/imgs/pages/img-11.jpg') }}"
                         alt="" width="900" height="900" loading="lazy" decoding="async">

                    @if (filled($pastille))
                        {{--
                            Médaillon tournant. Purement décoratif : le texte
                            qu'il porte est déjà dit par la section, et une
                            rotation continue n'est pas lisible au clavier.
                        --}}
                        <svg class="phare__pastille" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
                            <defs>
                                <path id="phare-cercle" fill="none"
                                      d="M60,60 m-45,0 a45,45 0 1,1 90,0 a45,45 0 1,1 -90,0" />
                            </defs>

                            <circle class="phare__pastille-fond" cx="60" cy="60" r="60" />

                            <g class="phare__pastille-texte">
                                <text><textPath href="#phare-cercle" startOffset="0">{{ $pastille }}</textPath></text>
                            </g>

                            <circle class="phare__pastille-coeur" cx="60" cy="60" r="22" />
                            <path class="phare__pastille-fleche" transform="translate(52 52)"
                                  d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" />
                        </svg>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
