@props([
    'eyebrow' => null,
    'position' => null,
    'total' => null,
    'name',
    'description' => null,
    'points' => [],
    'image' => null,
    'alt' => null,
    'href' => null,
])

{{--
    Bloc « track » repris de services-details-3 : libellé + rang à gauche,
    titre/texte/liste au centre, visuel à droite. Réutilisable pour toute liste
    d'éléments décrits ; ici, une filière au sein d'un cycle.
--}}
<article class="sd3-track">
    <div class="sd3-track__meta">
        @if ($eyebrow)
            <span>{{ $eyebrow }}</span>
        @endif
        @if ($position && $total)
            <strong>{{ str_pad((string) $position, 2, '0', STR_PAD_LEFT) }} / {{ str_pad((string) $total, 2, '0', STR_PAD_LEFT) }}</strong>
        @endif
    </div>

    <div class="sd3-track__body">
        <h3 class="sd3-track__name">
            @if ($href)
                <a href="{{ $href }}">{{ $name }}</a>
            @else
                {{ $name }}
            @endif
        </h3>

        @if ($description)
            <p>{{ $description }}</p>
        @endif

        @if (filled($points))
            <ul>
                @foreach ($points as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="sd3-track__media">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $alt ?? $name }}" width="560" height="420" loading="lazy" decoding="async">
        @else
            {{-- Aucune image renseignée : on garde le cadre pour ne pas casser la grille. --}}
            <span aria-hidden="true" class="sd3-track__placeholder"></span>
        @endif
    </div>
</article>
