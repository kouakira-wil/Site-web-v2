@props([
    'images',
    'label' => null,
])

@php
    $total = $images->count();

    // On choisit le nombre de colonnes qui remplit les rangées : trois quand le
    // total est un multiple de trois, deux sinon. Cela évite la dernière ligne
    // bancale d'une grille à pas fixe.
    $troisColonnes = $total > 3 && $total % 3 === 0;

    $colonnes = match (true) {
        $total === 1 => 'grid-cols-1',
        $troisColonnes => 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-3',
        default => 'grid-cols-1 sm:grid-cols-2',
    };

    // Sur une grille à deux colonnes, un total impair laisserait une image
    // orpheline : la première passe alors en pleine largeur et sert d'ouverture.
    $premiereEnLarge = ! $troisColonnes && $total > 1 && $total % 2 === 1;
@endphp

@if ($total > 0)
    <div {{ $attributes->class(['post-gallery mb-60']) }}
         @if ($label) role="group" aria-label="{{ $label }}" @endif>
        <div class="grid gap-4 {{ $colonnes }}">
            @foreach ($images as $image)
                @php
                    $enLarge = $premiereEnLarge && $loop->first;
                    $legende = $image->caption;
                @endphp

                <figure @class([
                    'post-gallery__item',
                    'sm:col-span-2' => $enLarge,
                ])>
                    {{-- .popup-image active la visionneuse du gabarit, navigation comprise. --}}
                    <a href="{{ $image->url() }}"
                       class="popup-image post-gallery__frame"
                       @if ($legende) title="{{ $legende }}" @endif
                       aria-label="Agrandir : {{ $legende ?? 'image '.$loop->iteration.' sur '.$total }}">
                        <img src="{{ $image->url() }}"
                             alt="{{ $legende ?? '' }}"
                             loading="lazy"
                             decoding="async">
                    </a>

                    @if ($legende)
                        <figcaption class="post-gallery__caption">{{ $legende }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
@endif
