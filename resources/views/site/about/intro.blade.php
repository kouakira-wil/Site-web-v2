@php
    $bloc = \App\Models\PageHeader::forKey('ecole.presentation');

    $arguments = \App\Models\PageHeader::query()
        ->where('key', 'like', 'ecole.presentation.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($carte): bool => filled($carte->title))
        ->values();
@endphp

@if ($bloc)
    {{--
        Première section de la page L'école.

        Deux colonnes : la photo à gauche, le discours à droite — surtitre,
        titre, accroche, deux arguments à pictogramme, points clés et bouton.
        Tout se règle depuis le back-office, rubrique « Textes du site ».
    --}}
    <section class="presentation-area pt-150 pb-100 !overflow-hidden" aria-labelledby="titre-presentation-ecole">
        <div class="container">
            <div class="presentation-grid">
                @if ($bloc->imageUrl())
                    <div class="presentation-media">
                        <img class="presentation-media__main" src="{{ $bloc->imageUrl() }}"
                             alt="" width="1200" height="1400" loading="lazy" decoding="async">

                        {{-- Seconde photo, superposée. Purement décorative : le
                             texte de la section porte déjà le propos. --}}
                        @if ($bloc->secondaryImageUrl())
                            <img class="presentation-media__inset" src="{{ $bloc->secondaryImageUrl() }}"
                                 alt="" width="700" height="600" loading="lazy" decoding="async">
                        @endif
                    </div>
                @endif

                <div class="presentation-body">
                    @if (filled($bloc->eyebrow))
                        <p class="sd3-label presentation-body__eyebrow at_fade_anim" data-fade-from="bottom">{{ $bloc->eyebrow }}</p>
                    @endif

                    <h2 id="titre-presentation-ecole" class="presentation-body__title reveal-text">{{ $bloc->title }}</h2>

                    @if (filled($bloc->intro))
                        <p class="presentation-body__lead">{{ $bloc->intro }}</p>
                    @endif

                    @if ($arguments->isNotEmpty())
                        <ul class="presentation-args">
                            @foreach ($arguments as $argument)
                                <li class="presentation-args__item">
                                    @if (filled($argument->icon))
                                        <span class="presentation-args__icon" aria-hidden="true">
                                            <x-site.icon :name="$argument->icon" class="w-6 h-6" />
                                        </span>
                                    @endif

                                    <span class="presentation-args__text">
                                        <strong class="presentation-args__title">{{ $argument->title }}</strong>
                                        @if (filled($argument->intro))
                                            <span class="presentation-args__lead">{{ $argument->intro }}</span>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($bloc->tagList() !== [])
                        <ul class="presentation-points">
                            @foreach ($bloc->tagList() as $point)
                                <li class="presentation-points__item">{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if (filled($bloc->cta_label))
                        <div class="presentation-body__actions">
                            <a class="at-btn !rounded-none" href="{{ $bloc->cta_url ?: route('projects.index') }}">
                                <span><span class="text-1">{{ $bloc->cta_label }}</span><span class="text-2">{{ $bloc->cta_label }}</span></span>
                                <i>
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                        <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                    </svg>
                                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true">
                                        <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                    </svg>
                                </i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
