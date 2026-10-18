@php
    $bloc = \App\Models\PageHeader::forKey('ecole.reperes');

    /*
     * Les chiffres sont comptés en base plutôt que saisis : ouvrir une filière
     * ou un cycle met la bande à jour toute seule, sans risque d'oubli.
     *
     * Un « pôle » est une catégorie qui porte au moins une filière publiée —
     * la table `categories` sert aussi aux rubriques d'actualités.
     */
    $reperes = [
        \App\Models\Cycle::query()->count(),
        \App\Models\Project::query()->published()->count(),
        \App\Models\Category::query()->whereHas('projects', fn ($q) => $q->published())->count(),
    ];

    $libelles = $bloc?->tagList() ?? [];
    $defauts = ['Cycles de formation', 'Filières ouvertes', 'Pôles d’excellence'];
@endphp

@if ($bloc)
    <section class="reperes-area pb-120" aria-labelledby="titre-reperes">
        <div class="container">
            <h2 id="titre-reperes" class="sr-only">{{ $bloc->title ?: 'L’école en chiffres' }}</h2>

            <div class="reperes-grid">
                <ul class="reperes-chiffres">
                    @foreach ($reperes as $index => $valeur)
                        <li class="reperes-chiffre">
                            {{-- Pas de « + » : ces nombres sont comptés en base,
                                 ils sont exacts et non approchés. --}}
                            <span class="reperes-chiffre__valeur">{{ $valeur }}</span>
                            <span class="reperes-chiffre__libelle">{{ $libelles[$index] ?? $defauts[$index] }}</span>
                        </li>
                    @endforeach
                </ul>

                @if ($bloc->imageUrl() || $bloc->secondaryImageUrl())
                    <div class="reperes-media">
                        @if ($bloc->secondaryImageUrl())
                            <img class="reperes-media__petite" src="{{ $bloc->secondaryImageUrl() }}"
                                 alt="" width="350" height="450" loading="lazy" decoding="async">
                        @endif

                        @if ($bloc->imageUrl())
                            <img class="reperes-media__grande" src="{{ $bloc->imageUrl() }}"
                                 alt="" width="650" height="450" loading="lazy" decoding="async">
                        @endif
                    </div>
                @endif

                @if (filled($bloc->cta_label))
                    <a class="reperes-pastille" href="{{ $bloc->cta_url ?: route('contact') }}">
                        <span class="reperes-pastille__fleche" aria-hidden="true">
                            <svg width="18" height="18" viewBox="0 0 11 11" fill="none">
                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                            </svg>
                        </span>
                        <span class="reperes-pastille__texte">{{ $bloc->cta_label }}</span>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif
