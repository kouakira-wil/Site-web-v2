@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'autocomplete' => null,
])

@php
    $id = 'champ-'.$name;
    $errorId = $id.'-erreur';
    $hasError = $errors->has($name);
    $control = 'w-full border-0 border-b bg-transparent px-0 py-3 text-ink placeholder-ink/30 focus:border-accent focus:ring-0 '
        .($hasError ? 'border-red-500' : 'border-ink/25');
@endphp

<div @class(['space-y-2', 'sm:col-span-2' => $type === 'textarea'])>
    <label for="{{ $id }}" class="block text-xs uppercase tracking-[0.16em] text-ink/50">
        {{ $label }}
        @unless ($required)
            <span class="normal-case tracking-normal text-ink/35">(facultatif)</span>
        @endunless
    </label>

    @if ($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            rows="6"
            @required($required)
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            class="{{ $control }} resize-y"
        >{{ old($name) }}</textarea>
    @else
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name) }}"
            @required($required)
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            class="{{ $control }}"
        >
    @endif

    @error($name)
        <p id="{{ $errorId }}" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
