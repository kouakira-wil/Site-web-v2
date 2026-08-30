@props(['title', 'subtitle' => null])

<header class="mb-10 flex flex-wrap items-end justify-between gap-6">
    <div>
        <h1 class="font-display text-display-sm">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-2 text-sm text-ink/55">{{ $subtitle }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex flex-wrap items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</header>
