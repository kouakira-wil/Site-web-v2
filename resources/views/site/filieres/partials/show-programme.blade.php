@php
    // Chaque champ est saisi une entrée par ligne dans le back-office.
    $colonnes = collect([
        ['titre' => 'Objectifs de la formation', 'items' => $project->lines('objectives')],
        ['titre' => 'Compétences clés', 'items' => $project->lines('skills')],
        ['titre' => 'Débouchés professionnels', 'items' => $project->lines('outcomes')],
        ['titre' => 'Public visé', 'items' => $project->lines('audience')],
    ])->filter(fn (array $colonne): bool => $colonne['items'] !== [])->values();
@endphp

@if ($colonnes->isNotEmpty())
    <section class="sd3-tracks pt-40 pb-20" aria-labelledby="titre-programme">
        <div class="container">
            <div class="sd3-tracks__head">
                <p class="sd3-label mb-15 at_fade_anim" data-fade-from="bottom">Programme</p>
                <h2 id="titre-programme" class="sd3-tracks__title reveal-text !mb-0">Le programme en détail</h2>
            </div>

            {{-- Deux colonnes plutôt que quatre : chaque item garde une largeur
                 de lecture confortable au lieu d'être coupé tous les deux mots. --}}
            <div class="programme-grid pt-40">
                @foreach ($colonnes as $colonne)
                    <div class="programme-col">
                        <span class="programme-col__index" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="programme-col__title">{{ $colonne['titre'] }}</h3>
                        <ul class="programme-col__list">
                            @foreach ($colonne['items'] as $item)
                                <li class="programme-col__item">{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
