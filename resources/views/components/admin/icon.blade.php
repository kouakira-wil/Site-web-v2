@props(['name'])

{{--
    Icônes du back-office, en SVG inline : pas de police d'icônes à charger,
    et la couleur suit `currentColor`. Traits arrondis de 1.6, comme la maquette.
--}}

@php
    $traces = [
        'gauge' => '<path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/><path d="m13.4 10.6 3.6-3.6"/><path d="M4.2 18a9 9 0 1 1 15.6 0"/>',
        'layers' => '<path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5"/>',
        'news' => '<path d="M4 5h13a1 1 0 0 1 1 1v12a2 2 0 0 0 2 2H5a2 2 0 0 1-2-2V6a1 1 0 0 1 1-1Z"/><path d="M18 8h2a1 1 0 0 1 1 1v9a2 2 0 0 1-2 2"/><path d="M7 9h7M7 13h7M7 17h4"/>',
        'users' => '<path d="M16 19v-1a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v1"/><circle cx="9" cy="7" r="3.2"/><path d="M17 11a3 3 0 1 0 0-6"/><path d="M22 19v-1a4 4 0 0 0-3-3.8"/>',
        'heading' => '<path d="M5 5v14M13 5v14M5 12h8"/><path d="M17 19v-7l4 2.5"/>',
        'mail' => '<rect x="2.5" y="5" width="19" height="14" rx="2"/><path d="m3 7 8.1 5.4a1.6 1.6 0 0 0 1.8 0L21 7"/>',
        'bell' => '<path d="M18 8a6 6 0 1 0-12 0c0 6-2 7-2 7h16s-2-1-2-7"/><path d="M13.7 20a2 2 0 0 1-3.4 0"/>',
        'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m21 21-5.9-5.9"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'external' => '<path d="M13 5h6v6"/><path d="M19 5 11 13"/><path d="M18 14v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4"/>',
        'logout' => '<path d="M9 20H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3"/><path d="m15 16 4-4-4-4"/><path d="M19 12H9"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'download' => '<path d="M12 3v12"/><path d="m8 11 4 4 4-4"/><path d="M4 19h16"/>',
        'quote' => '<path d="M9.5 6C6.5 7 5 9.4 5 13v5h5v-5H7.6c0-2.6.9-4.2 2.8-5Z"/><path d="M18.5 6c-3 1-4.5 3.4-4.5 7v5h5v-5h-2.4c0-2.6.9-4.2 2.8-5Z"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="16" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
    ];

    $trace = $traces[$name] ?? null;
@endphp

@if ($trace)
    <svg {{ $attributes->merge(['class' => 'h-5 w-5']) }}
         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
        {!! $trace !!}
    </svg>
@endif
