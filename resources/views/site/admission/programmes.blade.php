@php
    $section = \App\Models\PageHeader::forKey('admission.programmes');
@endphp

@if ($section && $cycles->isNotEmpty())
    {{--
        Tableau des filières, filtré par cycle puis par pôle.

        Le tri se fait dans le navigateur : les lignes sont toutes dans la page,
        chacune étiquetée de ses cycles et de son pôle. Sans script, les filtres
        ne font rien mais le tableau reste entièrement lisible — c'est pour cela
        qu'aucune ligne n'est masquée côté serveur.
    --}}
    <section class="adm-section" aria-labelledby="titre-programmes" data-programmes>
        <div class="container">
            <header class="adm-tete">
                <h2 id="titre-programmes" class="adm-tete__titre reveal-text">{{ $section->title }}</h2>

                @if (filled($section->intro))
                    <p class="adm-tete__intro">{{ $section->intro }}</p>
                @endif
            </header>

            <div class="adm-onglets" role="group" aria-label="Filtrer par cycle">
                @foreach ($cycles as $cycle)
                    <button type="button" class="adm-onglet{{ $loop->first ? ' is-active' : '' }}"
                            data-cycle="{{ $cycle->slug }}"
                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $cycle->short_label ?: $cycle->name }}</button>
                @endforeach
            </div>

            @if ($poles->isNotEmpty())
                <div class="adm-poles" role="group" aria-label="Filtrer par pôle">
                    <button type="button" class="adm-pole is-active" data-pole="all" aria-pressed="true">Tous les pôles</button>

                    @foreach ($poles as $pole)
                        <button type="button" class="adm-pole" data-pole="{{ $pole->slug }}"
                                aria-pressed="false">{{ $pole->name }}</button>
                    @endforeach
                </div>
            @endif

            <div class="adm-tableau">
                <table class="adm-table">
                    <caption class="sr-only">Filières par cycle et par pôle, avec leurs débouchés</caption>

                    <thead>
                        <tr>
                            <th scope="col">Filière</th>
                            <th scope="col">Débouchés</th>
                            <th scope="col"><span class="sr-only">Action</span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($filieres as $filiere)
                            @php
                                // Les débouchés sont saisis en texte libre, une ligne par métier.
                                $debouches = collect(preg_split('/\R+/', (string) $filiere->outcomes, -1, PREG_SPLIT_NO_EMPTY))
                                    ->map(fn (string $metier): string => trim($metier))
                                    ->filter()
                                    ->take(3);
                            @endphp

                            <tr class="adm-ligne"
                                data-cycles="{{ $filiere->cycles->pluck('slug')->implode(' ') }}"
                                data-pole="{{ $filiere->category?->slug }}">
                                <th scope="row" class="adm-ligne__nom">{{ $filiere->title }}</th>

                                <td class="adm-ligne__debouches">{{ $debouches->implode(', ') }}</td>

                                <td class="adm-ligne__action">
                                    <a class="adm-lien" href="{{ route('projects.show', $filiere) }}">
                                        En savoir plus
                                        <span class="sr-only">sur {{ $filiere->title }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Révélé par le script quand aucune ligne ne reste visible. --}}
                <p class="adm-tableau__vide" data-programmes-vide hidden>Aucune filière ne correspond à ce choix.</p>
            </div>
        </div>
    </section>
@endif
