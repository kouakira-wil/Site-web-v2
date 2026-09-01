@php
    $blocs = \App\Models\PageHeader::query()
        ->where('group', 'equipe')
        ->orderBy('position')
        ->get();

    $entete = $blocs->firstWhere('key', 'equipe.approche');
    // Les cartes sont tous les blocs sauf l'en-tête, et seulement celles qui
    // portent un titre : un bloc vidé disparaît au lieu de laisser un trou.
    $cartes = $blocs->where('key', '!=', 'equipe.approche')->filter(fn ($c): bool => filled($c->title))->values();
@endphp

@if ($entete && $cartes->isNotEmpty())
    {{--
        Bandeau bleu de marque, cartes sur fond encre. Les textes et le nom des
        pictogrammes se règlent dans le back-office, rubrique « Textes du site ».
    --}}
    <section class="approche-area" aria-labelledby="titre-approche">
        <div class="container">
            <div class="approche-area__head">
                <h2 id="titre-approche" class="approche-area__title">{{ $entete->title }}</h2>

                @if (filled($entete->intro))
                    <p class="approche-area__lead">{{ $entete->intro }}</p>
                @endif
            </div>

            <ul class="approche-grid">
                @foreach ($cartes as $carte)
                    <li class="approche-card">
                        @if (filled($carte->icon))
                            <span class="approche-card__icon">
                                <x-site.icon :name="$carte->icon" class="w-9 h-9" />
                            </span>
                        @endif

                        <h3 class="approche-card__title">{{ $carte->title }}</h3>

                        @if (filled($carte->intro))
                            <p class="approche-card__text">{{ $carte->intro }}</p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
