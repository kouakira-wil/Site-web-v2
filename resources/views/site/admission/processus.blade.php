@php
    $section = \App\Models\PageHeader::forKey('admission.processus');

    $etapes = \App\Models\PageHeader::query()
        ->where('key', 'like', 'admission.processus.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($etape): bool => filled($etape->title))
        ->values();
@endphp

@if ($section && $etapes->isNotEmpty())
    {{--
        Les étapes du dossier, dans l'ordre. Le rang est calculé : réordonner
        les blocs au back-office suffit à renuméroter les cartes.
    --}}
    <section class="adm-section adm-section--gris" aria-labelledby="titre-processus">
        <div class="container">
            <header class="adm-tete">
                <h2 id="titre-processus" class="adm-tete__titre reveal-text">{{ $section->title }}</h2>

                @if (filled($section->intro))
                    <p class="adm-tete__intro">{{ $section->intro }}</p>
                @endif
            </header>

            <ol class="adm-etapes">
                @foreach ($etapes as $etape)
                    <li class="adm-etape">
                        @if (filled($etape->icon))
                            <span class="adm-etape__icone" aria-hidden="true">
                                <x-site.icon :name="$etape->icon" class="w-7 h-7" />
                            </span>
                        @endif

                        <h3 class="adm-etape__titre">{{ $etape->title }}</h3>

                        @if (filled($etape->intro))
                            <p class="adm-etape__texte">{{ $etape->intro }}</p>
                        @endif

                        {{-- Le rang est déjà porté par la liste ordonnée : la
                             pastille le répète pour l'œil, pas pour l'oreille. --}}
                        <span class="adm-etape__rang" aria-hidden="true">Étape {{ $loop->iteration }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif
