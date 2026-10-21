{{--
    Entrée « Filières » du menu principal.

    Avec au moins un cycle pourvu, elle déroule un méga-menu listant les
    filières par cycle ; sinon elle reste un simple lien, pour ne pas ouvrir un
    panneau vide.
--}}
@if ($cycles->isEmpty())
    <li>
        <a href="{{ route('projects.index') }}">Filières</a>
    </li>
@else
    <li class="has-dropdown">
        <a href="{{ route('projects.index') }}">Filières</a>
        {{--
            Une colonne par cycle, sur une seule ligne. La grille du gabarit
            donnait à chaque colonne la moitié du panneau : à trois cycles, les
            colonnes totalisaient une fois et demie sa largeur et débordaient.
        --}}
        <div class="at-submenu submenu at-megamenu filieres-menu"
             style="--filieres-menu-colonnes: {{ $cycles->count() }}">
            <div class="filieres-menu__grille">
                @foreach ($cycles as $cycle)
                    <div class="filieres-menu__colonne">
                        <div class="at-megamenu-box">
                            <a class="at-megamenu-title-wrap" href="{{ route('projects.cycle', $cycle) }}">
                                <span class="at-megamenu-title">{{ $cycle->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M10.0208 3.41421L1.41421 12.0208L0 10.6066L8.60659 2H1.02082V0H12.0208V11H10.0208V3.41421Z" fill="currentColor" />
                                </svg>
                            </a>
                            <ul>
                                @foreach ($cycle->projects as $filiere)
                                    <li>
                                        <a href="{{ route('projects.show', $filiere) }}">{{ $filiere->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </li>
@endif
