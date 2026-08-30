@props([
    'url' => null,
    'alt' => '',
    'label' => '',
    'ratio' => 'aspect-[4/3]',
    'eager' => false,
])

{{-- Renders a cover image, or a typographic placeholder when none is set yet. --}}
<div {{ $attributes->class(['relative overflow-hidden bg-paper-warm', $ratio]) }}>
    @if ($url)
        <img
            src="{{ $url }}"
            alt="{{ $alt }}"
            loading="{{ $eager ? 'eager' : 'lazy' }}"
            @if ($eager) fetchpriority="high" @endif
            decoding="async"
            class="h-full w-full object-cover transition-transform duration-700 ease-out-expo group-hover:scale-[1.04]"
        >
    @else
        <div class="flex h-full w-full items-end bg-gradient-to-br from-paper-warm via-paper-dim to-paper-warm p-6">
            <span aria-hidden="true" class="font-display text-4xl text-ink/25">
                {{ Str::of($label)->substr(0, 2)->upper() }}
            </span>
        </div>
    @endif
</div>
