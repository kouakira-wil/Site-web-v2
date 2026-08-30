@props([
    'label',
    'for' => null,
    'hint' => null,
    'error' => null,
])

<div class="space-y-2">
    <label @if ($for) for="{{ $for }}" @endif class="block text-xs font-medium uppercase tracking-[0.14em] text-ink/50">
        {{ $label }}
    </label>

    {{ $slot }}

    @if ($hint && ! $error)
        <p class="text-xs text-ink/45">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
