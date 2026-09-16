@php
    $mission = \App\Models\PageHeader::forKey('ecole.mission');
@endphp

@if ($mission && (filled($mission->intro) || $mission->tagList() !== []))
    {{--
        Section de la mission, en deux moitiés pleine largeur : photo à gauche,
        panneau sombre à droite. Le conteneur du gabarit n'est pas utilisé ici —
        les deux moitiés touchent les bords — mais le texte reprend ses marges
        pour rester aligné sur le reste de la page.
    --}}
    <section class="mission-split" aria-labelledby="titre-mission">
        @if ($mission->imageUrl())
            <div class="mission-split__media">
                <img src="{{ $mission->imageUrl() }}" alt="" width="1200" height="1000"
                     loading="lazy" decoding="async">
            </div>
        @endif

        <div class="mission-split__panel">
            <div class="mission-split__inner">
                <h2 id="titre-mission" class="mission-split__title reveal-text">{{ $mission->title }}</h2>

                @if (filled($mission->intro))
                    <p class="mission-split__lead">{{ $mission->intro }}</p>
                @endif

                @if ($mission->tagList() !== [])
                    <ul class="mission-split__list">
                        @foreach ($mission->tagList() as $item)
                            <li class="mission-split__item">
                                <span class="mission-split__check" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                        <path d="M3 9.5L7 13.5L15 4.5" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <span class="mission-split__texte">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
@endif
