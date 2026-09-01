@props(['name' => null])

{{--
    Pictogrammes des cartes du site, en SVG inline : rien à charger et la
    couleur suit `currentColor`. Le nom se choisit dans le back-office ; un nom
    inconnu ne rend rien plutôt qu'un carré vide.
--}}

@php
    $traces = [
        'book' => '<path d="M3 5.5A1.5 1.5 0 0 1 4.5 4H9a3 3 0 0 1 3 3v12a2.5 2.5 0 0 0-2.5-2.5H3Z"/><path d="M21 5.5A1.5 1.5 0 0 0 19.5 4H15a3 3 0 0 0-3 3v12a2.5 2.5 0 0 1 2.5-2.5H21Z"/>',
        'briefcase' => '<rect x="2.5" y="7.5" width="19" height="12.5" rx="2"/><path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5"/><path d="M2.5 12.5h19"/>',
        'users' => '<circle cx="9" cy="8" r="3.4"/><path d="M2.5 20v-1.2A4.3 4.3 0 0 1 6.8 14.5h4.4a4.3 4.3 0 0 1 4.3 4.3V20"/><path d="M16.5 5.2a3.4 3.4 0 0 1 0 5.6"/><path d="M21.5 20v-1.2a4.3 4.3 0 0 0-3.2-4.1"/>',
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/>',
        'target' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/>',
        'award' => '<circle cx="12" cy="9" r="5.5"/><path d="m8.5 13.8-1.4 6.9 4.9-2.6 4.9 2.6-1.4-6.9"/>',
        'sparkle' => '<path d="M12 3.5 13.9 9 19.5 11l-5.6 2L12 18.5 10.1 13 4.5 11l5.6-2Z"/><path d="M18.5 4v3M20 5.5h-3"/>',
        'compass' => '<circle cx="12" cy="12" r="9"/><path d="m15.5 8.5-2 5.2-5.2 2 2-5.2Z"/>',
    ];

    $trace = $name ? ($traces[$name] ?? null) : null;
@endphp

@if ($trace)
    <svg {{ $attributes->merge(['class' => 'w-8 h-8']) }}
         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
        {!! $trace !!}
    </svg>
@endif
