@props(['eyebrow' => null, 'title', 'lede' => null])

<header class="shell pb-16 pt-16 sm:pt-24">
    @if ($eyebrow)
        <p class="text-xs uppercase tracking-[0.22em] text-accent">{{ $eyebrow }}</p>
    @endif

    <h1 class="mt-5 max-w-4xl font-display text-display-lg text-balance">{{ $title }}</h1>

    @if ($lede)
        <p class="mt-6 max-w-prose text-lede text-ink/65">{{ $lede }}</p>
    @endif
</header>
