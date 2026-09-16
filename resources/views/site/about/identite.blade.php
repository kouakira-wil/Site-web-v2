@php
    $blocs = \App\Models\PageHeader::query()
        ->where('group', 'ecole')
        // Le mot du directeur partage ce groupe mais a sa propre section.
        ->where('key', '!=', 'ecole.directeur')
        // La présentation a sa propre section en tête de page.
        ->where('key', 'not like', 'ecole.presentation%')
        ->where('key', '!=', 'ecole.reperes')
        // La mission a sa propre section, en pleine largeur.
        ->where('key', '!=', 'ecole.mission')
        ->where('key', 'not like', 'ecole.vision%')
        ->orderBy('position')
        ->get()
        // Un bloc sans texte ni liste n'a rien à montrer : on ne rend pas un
        // titre suivi du vide.
        ->filter(fn ($bloc): bool => filled($bloc->intro) || $bloc->tagList() !== [])
        ->values();
@endphp

@if ($blocs->isNotEmpty())
    {{--
        Vision, mission, objectif et valeurs.

        Même grille que « Le programme en détail » des fiches filières :
        deux colonnes, index en bleu, filet de séparation. Les textes se
        saisissent dans le back-office, rubrique « Textes du site ».
    --}}
    <section class="sd3-tracks pt-100 pb-60" aria-labelledby="titre-identite">
        <div class="container">
            <div class="sd3-tracks__head">
                <p class="sd3-label mb-15 at_fade_anim" data-fade-from="bottom">Notre identité</p>
                <h2 id="titre-identite" class="sd3-tracks__title reveal-text !mb-0">Ce qui nous guide</h2>
            </div>

            <div class="programme-grid pt-40">
                @foreach ($blocs as $bloc)
                    <div class="programme-col">
                        <span class="programme-col__index" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="programme-col__title">{{ $bloc->title }}</h3>

                        @if (filled($bloc->intro))
                            <p class="programme-col__lead">{{ $bloc->intro }}</p>
                        @endif

                        @if ($bloc->tagList() !== [])
                            <ul class="programme-col__list">
                                @foreach ($bloc->tagList() as $point)
                                    <li class="programme-col__item">{{ $point }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
