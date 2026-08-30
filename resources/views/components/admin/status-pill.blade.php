@props(['status'])

@php
    $map = [
        'published' => ['Publié', 'bg-emerald-100 text-emerald-800'],
        'draft' => ['Brouillon', 'bg-amber-100 text-amber-800'],
        'new' => ['Nouveau', 'bg-accent/15 text-accent-deep'],
        'read' => ['Lu', 'bg-ink/10 text-ink/70'],
        'replied' => ['Répondu', 'bg-emerald-100 text-emerald-800'],
        'archived' => ['Archivé', 'bg-ink/5 text-ink/50'],
    ];

    [$label, $classes] = $map[$status] ?? [$status, 'bg-ink/10 text-ink/70'];
@endphp

<span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $classes }}">{{ $label }}</span>
