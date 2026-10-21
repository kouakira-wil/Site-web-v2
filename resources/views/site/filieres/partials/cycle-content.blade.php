@php
    // Replis pour les filières sans couverture : les visuels du gabarit
    // (.webp) ne sont pas dans le dépôt.
    $replis = ['img-11.jpg', 'img-12.jpg', 'img-13.jpg', 'img-14.jpg'];
@endphp

<div class="sec-1-portfolio-1 !overflow-hidden pt-120 pb-110 border-bottom-100">
    <div class="container">
        <header class="filieres-groupe__tete">
            <p class="filieres-groupe__compte" data-groupe-compte>{{ $filieres->count() }} filière{{ $filieres->count() > 1 ? 's' : '' }} ouverte{{ $filieres->count() > 1 ? 's' : '' }} en {{ $cycle->name }}</p>
        </header>

        @if ($poles->count() > 1)
            {{--
                Le filtre ne paraît qu'avec au moins deux pôles : avec un seul,
                le bouton « Tous » et le bouton du pôle révéleraient la même
                chose.

                `initPortfolioFilter()` du gabarit masque et révèle les fiches
                selon `data-filter` / `data-category`, sans rechargement.
            --}}
            <div class="filter-portfolio !flex !flex-wrap !items-center !justify-center !gap-2 pb-40"
                 role="group" aria-label="Filtrer par pôle">
                <button type="button" data-filter="all" class="at-btn filter-btn btn-sm active"
                        aria-pressed="true">Tous les pôles</button>

                @foreach ($poles as $pole)
                    <button type="button" data-filter="{{ $pole->slug }}" class="at-btn filter-btn btn-sm"
                            aria-pressed="false">{{ $pole->name }}</button>
                @endforeach
            </div>
        @endif

        <div class="!justify-center !items-stretch flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
            @forelse ($filieres as $filiere)
                <x-site.filiere-item :filiere="$filiere"
                    class="w-full 2xl:w-1/2 lg:w-7/12"
                    :fallback="asset('assets/imgs/pages/'.$replis[$loop->index % count($replis)])" />
            @empty
                <p class="filieres-vide">Aucune filière n’est ouverte en {{ $cycle->name }} pour le moment.</p>
            @endforelse
        </div>

        {{-- Révélé par le script quand le filtre ne laisse rien. --}}
        <p class="filieres-vide" data-filieres-vide hidden>Aucune filière de ce pôle dans ce cycle.</p>
    </div>
</div>

@if ($autresCycles->isNotEmpty())
    {{--
        Les autres cycles, pour passer de l'un à l'autre sans repasser par la
        liste complète.
    --}}
    <section class="cycles-voisins" aria-labelledby="titre-autres-cycles">
        <div class="container">
            <h2 id="titre-autres-cycles" class="cycles-voisins__titre">Les autres cycles</h2>

            <ul class="cycles-voisins__liste">
                @foreach ($autresCycles as $autre)
                    <li>
                        <a class="cycles-voisins__lien" href="{{ route('projects.cycle', $autre) }}">
                            <span class="cycles-voisins__nom">{{ $autre->name }}</span>

                            @if (filled($autre->short_label))
                                <span class="cycles-voisins__niveau">{{ $autre->short_label }}</span>
                            @endif

                            <span class="cycles-voisins__compte">{{ $autre->projects_count }} filière{{ $autre->projects_count > 1 ? 's' : '' }}</span>
                        </a>
                    </li>
                @endforeach

                <li>
                    <a class="cycles-voisins__lien cycles-voisins__lien--tout" href="{{ route('projects.index') }}">
                        <span class="cycles-voisins__nom">Toutes les filières</span>
                        <span class="cycles-voisins__compte">tous cycles confondus</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endif
