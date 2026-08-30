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
        <div class="at-submenu submenu at-megamenu">
            <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                @foreach ($cycles as $cycle)
                    <div class="w-full xl:w-1/2 col-xl-6">
                        <div class="at-megamenu-box">
                            <div class="at-megamenu-title-wrap">
                                <span class="at-megamenu-title">{{ $cycle->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
                                    <path d="M10.0208 3.41421L1.41421 12.0208L0 10.6066L8.60659 2H1.02082V0H12.0208V11H10.0208V3.41421Z" fill="currentColor" />
                                </svg>
                            </div>
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
