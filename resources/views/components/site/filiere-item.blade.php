@props([
    'filiere',
    'fallback' => null,
    'class' => '',
])

{{-- Ligne de filière du gabarit (mg-portfolio-item), alimentée par la base. --}}
<div class="mg-portfolio-item anim-zoomin-wrap mb-55 {{ $class }}" data-category="{{ $filiere->category?->slug }}">
    <div class="mg-portfolio-thumb anim-zoomin not-hide-cursor">
        <a class="cursor-hide" href="{{ route('projects.show', $filiere) }}">
            <img data-speed=".8" class="!w-full"
                 src="{{ $filiere->coverUrl() ?? $fallback }}"
                 alt="Filière {{ $filiere->title }}" />
        </a>
    </div>
    <div class="mg-portfolio-content cs-portfolio-content !flex !items-center !flex-wrap md:!flex-nowrap !justify-between">
        <div class="w-md-75">
            <h3 class="h5 cs-portfolio-title at-title-anim fix mr-20 at-ff-sequel-semi-bold">
                <a href="{{ route('projects.show', $filiere) }}" class="at-title-text">{{ $filiere->title }}</a>
            </h3>
            <p class="fz-font-lg neutral-500">{{ $filiere->excerpt }}</p>
        </div>
        <div class="cs-portfolio-tag">
            <ul class="!flex md:!justify-end !flex-wrap !whitespace-nowrap">
                @foreach ($filiere->cycles as $cycle)
                    <li>
                        <a href="{{ route('projects.index') }}#{{ $cycle->slug }}">{{ $cycle->short_label ?? $cycle->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
