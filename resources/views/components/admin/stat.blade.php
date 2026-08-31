@props([
    'label',
    'value',
    'caption' => null,
    'icon' => 'layers',
    'tone' => 'blue',
    'trend' => null,
    'href' => null,
])

@php
    // Une variation nulle n'est pas une information : on n'affiche la pastille
    // que lorsqu'il y a réellement eu un mouvement.
    $variation = is_numeric($trend) ? (float) $trend : null;
    $enHausse = $variation !== null && $variation > 0;

    $teintes = [
        'blue' => 'bg-tile-blue text-tile-blue-ink',
        'amber' => 'bg-tile-amber text-tile-amber-ink',
        'cyan' => 'bg-tile-cyan text-tile-cyan-ink',
        'violet' => 'bg-tile-violet text-tile-violet-ink',
    ];

    $balise = $href ? 'a' : 'div';
@endphp

<{{ $balise }}
    @if ($href) href="{{ $href }}" @endif
    {{ $attributes->class([
        'block rounded-lg border border-app-line bg-app-card p-5 transition-shadow duration-200',
        'hover:shadow-[0_2px_12px_rgba(30,32,37,0.08)]' => (bool) $href,
    ]) }}
>
    <div class="flex items-start justify-between gap-4">
        <p class="text-xs font-semibold uppercase tracking-[0.06em] text-app-muted">{{ $label }}</p>

        @if ($variation !== null && $variation != 0)
            <span @class([
                'inline-flex shrink-0 items-center gap-1 rounded px-1.5 py-0.5 text-xs font-semibold',
                'bg-trend-up-bg text-trend-up' => $enHausse,
                'bg-trend-down-bg text-trend-down' => ! $enHausse,
            ])>
                <span aria-hidden="true">{{ $enHausse ? '▴' : '▾' }}</span>
                {{ number_format(abs($variation), 2, ',', ' ') }} %
                <span class="sr-only">{{ $enHausse ? 'en hausse' : 'en baisse' }} par rapport au mois précédent</span>
            </span>
        @endif
    </div>

    <div class="mt-4 flex items-end justify-between gap-4">
        <div class="min-w-0">
            <p class="text-[26px] font-semibold leading-none">{{ $value }}</p>
            @if ($caption)
                <p class="mt-3 truncate text-[13px] text-app-muted">{{ $caption }}</p>
            @endif
        </div>

        <span class="grid h-[52px] w-[52px] shrink-0 place-items-center rounded-md {{ $teintes[$tone] ?? $teintes['blue'] }}">
            <x-admin.icon :name="$icon" class="h-6 w-6" />
        </span>
    </div>
</{{ $balise }}>
