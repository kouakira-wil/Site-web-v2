@props(['variant' => 'primary', 'href' => null])

@php
    // Orisa's button metrics: 12px, medium weight, wide tracking (.at-btn).
    $base = 'at-btn-base';

    $styles = [
        'primary' => 'bg-brand text-neutral-0 hover:bg-brand-dark',
        'secondary' => 'border border-neutral-200 text-ink hover:border-brand hover:bg-brand hover:text-neutral-0',
        'danger' => 'border border-red-300 text-red-700 hover:bg-red-600 hover:text-white',
        'ghost' => 'text-ink-muted hover:text-ink',
    ];

    $classes = $base.' '.($styles[$variant] ?? $styles['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->class($classes)->merge(['type' => 'button']) }}>{{ $slot }}</button>
@endif
