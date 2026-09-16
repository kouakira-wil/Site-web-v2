@php
    $vision = \App\Models\PageHeader::forKey('ecole.vision');

    // Les étapes du parcours : connaissances, compétences, expérience.
    $etapes = \App\Models\PageHeader::query()
        ->where('key', 'like', 'ecole.vision.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($etape): bool => filled($etape->title))
        ->values();
@endphp

@if ($vision && (filled($vision->intro) || $vision->tagList() !== [] || $etapes->isNotEmpty()))
    {{--
        Section de la vision : photos rondes à gauche, discours à droite.
        Les trois étapes du parcours sont des blocs à part entière
        (ecole.vision.1 à .3), modifiables au back-office comme le reste.
    --}}
    <section class="vision-area" aria-labelledby="titre-vision">
        <div class="container">
            <div class="vision-grid">
                {{-- Sans photo, pas de colonne vide : le texte prend toute la largeur. --}}
                @if ($vision->imageUrl() || $vision->secondaryImageUrl())
                <div class="vision-collage">
                    @if ($vision->imageUrl())
                        <img class="vision-collage__haute" src="{{ $vision->imageUrl() }}"
                             alt="" width="700" height="700" loading="lazy" decoding="async">
                    @endif

                    @if ($vision->secondaryImageUrl())
                        <img class="vision-collage__basse" src="{{ $vision->secondaryImageUrl() }}"
                             alt="" width="800" height="800" loading="lazy" decoding="async">
                    @endif
                </div>
                @endif

                <div class="vision-body">
                    @if (filled($vision->eyebrow))
                        <p class="vision-body__eyebrow">
                            <span class="vision-body__puce" aria-hidden="true"></span>
                            {{ $vision->eyebrow }}
                        </p>
                    @endif

                    <h2 id="titre-vision" class="vision-body__title reveal-text">{{ $vision->title }}</h2>

                    @if (filled($vision->intro))
                        <div class="vision-carte">
                            @if (filled($vision->icon))
                                <span class="vision-carte__icone" aria-hidden="true">
                                    <x-site.icon :name="$vision->icon" class="w-8 h-8" />
                                </span>
                            @endif

                            <p class="vision-carte__texte">{{ $vision->intro }}</p>
                        </div>
                    @endif

                    @if ($etapes->isNotEmpty())
                        <ol class="vision-etapes">
                            @foreach ($etapes as $etape)
                                <li class="vision-etape">
                                    <span class="vision-etape__rang" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                                    <span class="vision-etape__corps">
                                        @if (filled($etape->eyebrow))
                                            <span class="vision-etape__etiquette">{{ $etape->eyebrow }}</span>
                                        @endif

                                        <strong class="vision-etape__titre">{{ $etape->title }}</strong>

                                        @if (filled($etape->intro))
                                            <span class="vision-etape__texte">{{ $etape->intro }}</span>
                                        @endif
                                    </span>

                                    @if (filled($etape->icon))
                                        <span class="vision-etape__icone" aria-hidden="true">
                                            <x-site.icon :name="$etape->icon" class="w-6 h-6" />
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endif

                    @if ($vision->tagList() !== [])
                        <ul class="vision-points">
                            @foreach ($vision->tagList() as $point)
                                <li class="vision-points__item">{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if (filled($vision->cta_label))
                        <div class="vision-actions">
                            <a class="vision-bouton" href="{{ $vision->cta_url ?: route('projects.index') }}">
                                {{ $vision->cta_label }}
                            </a>

                            {{-- Pastille décorative : le bouton mène déjà au même
                                 endroit, la dupliquer en lien ajouterait une
                                 tabulation sans destination nouvelle. --}}
                            <span class="vision-rond" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 11 11" fill="none">
                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
