@props([
    'title' => null,
    'actions' => null,
    'bodyClass' => 'p-5',
])

{{-- Carte du tableau de bord : fond blanc, filet fin, coins légèrement arrondis. --}}
<section {{ $attributes->class(['overflow-hidden rounded-lg border border-app-line bg-app-card']) }}>
    @if ($title || $actions)
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-app-line px-5 py-3.5">
            @if ($title)
                <h2 class="text-[15px] font-semibold">{{ $title }}</h2>
            @endif

            @if ($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </header>
    @endif

    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</section>
