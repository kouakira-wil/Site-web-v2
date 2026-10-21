@php
    $section = \App\Models\PageHeader::forKey('admission.criteres');

    $volets = \App\Models\PageHeader::query()
        ->where('key', 'like', 'admission.criteres.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($volet): bool => filled($volet->title))
        ->values();
@endphp

@if ($section && $volets->isNotEmpty())
    {{--
        Critères d'admission, un volet par cycle. L'accordéon est celui du
        gabarit : `data-at-collapse` relie le bouton au panneau par son
        identifiant, et `data-at-collapse-parent` referme les autres.
    --}}
    <section class="adm-section" aria-labelledby="titre-criteres">
        <div class="container">
            <header class="adm-tete">
                <h2 id="titre-criteres" class="adm-tete__titre reveal-text">{{ $section->title }}</h2>

                @if (filled($section->intro))
                    <p class="adm-tete__intro">{{ $section->intro }}</p>
                @endif
            </header>

            <div class="adm-volets" id="accordeon-criteres">
                @foreach ($volets as $volet)
                    @php
                        $panneau = 'critere-'.$loop->iteration;
                        $ouvert = $loop->first;
                    @endphp

                    <div class="adm-volet">
                        <button class="adm-volet__bouton{{ $ouvert ? '' : ' collapsed' }}" type="button"
                                data-at-collapse data-at-collapse-target="#{{ $panneau }}"
                                aria-expanded="{{ $ouvert ? 'true' : 'false' }}"
                                aria-controls="{{ $panneau }}">
                            <span>{{ $volet->title }}</span>

                            <span class="adm-volet__chevron" aria-hidden="true">
                                <svg width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 1L7 7L13 1" stroke="currentColor" stroke-width="1.8"
                                          stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>

                        <div id="{{ $panneau }}" class="at-collapse adm-volet__panneau{{ $ouvert ? ' is-open' : '' }}"
                             data-at-collapse-parent="#accordeon-criteres">
                            <div class="adm-volet__corps">
                                @forelse (preg_split('/\R{2,}/', (string) $volet->body, -1, PREG_SPLIT_NO_EMPTY) as $paragraphe)
                                    <p>{{ $paragraphe }}</p>
                                @empty
                                    {{-- Un volet sans texte le dit, plutôt que de s'ouvrir sur du vide. --}}
                                    <p class="adm-volet__vide">Les conditions de ce cycle seront précisées prochainement. Contactez le secrétariat pour les connaître dès aujourd’hui.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
