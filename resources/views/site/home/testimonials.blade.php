@php
    $s = \App\Models\PageHeader::forKey('home.testimonials');

    $cartes = \App\Models\PageHeader::query()
        ->where('key', 'like', 'home.testimonials.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($carte): bool => filled($carte->title))
        ->values();
@endphp

@if ($s)
    {{--
        Section « Pourquoi l'ESCAT ».

        Trois colonnes : photo à gauche, discours au centre, photo plus haute
        à droite. Les deux cartes sous le bouton sont des blocs à part entière
        (home.testimonials.1 et .2), modifiables au back-office.
    --}}
    <section class="pourquoi-area pt-120 pb-120" aria-labelledby="titre-pourquoi">
        <div class="container">
            <div class="pourquoi-grid">
                @if ($s->imageUrl())
                    <div class="pourquoi-media pourquoi-media--gauche">
                        <img src="{{ $s->imageUrl() }}" alt="" width="800" height="1000"
                             loading="lazy" decoding="async">
                    </div>
                @endif

                <div class="pourquoi-body">
                    @if (filled($s->eyebrow))
                        <p class="pourquoi-body__eyebrow">
                            @if (filled($s->icon))
                                <span class="pourquoi-body__icone" aria-hidden="true">
                                    <x-site.icon :name="$s->icon" class="w-4 h-4" />
                                </span>
                            @endif
                            {{ $s->eyebrow }}
                        </p>
                    @endif

                    <h2 id="titre-pourquoi" class="pourquoi-body__title reveal-text">{{ $s->title }}</h2>

                    @if (filled($s->intro))
                        <p class="pourquoi-body__lead">{{ $s->intro }}</p>
                    @endif

                    @if (filled($s->cta_label))
                        <div class="pourquoi-actions">
                            <a class="pourquoi-bouton" href="{{ $s->cta_url ?: route('contact') }}">{{ $s->cta_label }}</a>

                            {{-- Pastille décorative : le bouton mène déjà au même endroit. --}}
                            <span class="pourquoi-rond" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 11 11" fill="none">
                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    @endif

                    @if ($cartes->isNotEmpty())
                        <ul class="pourquoi-cartes">
                            @foreach ($cartes as $carte)
                                <li class="pourquoi-carte">
                                    @if (filled($carte->icon))
                                        <span class="pourquoi-carte__icone" aria-hidden="true">
                                            <x-site.icon :name="$carte->icon" class="w-7 h-7" />
                                        </span>
                                    @endif

                                    <strong class="pourquoi-carte__titre">{{ $carte->title }}</strong>

                                    @if (filled($carte->intro))
                                        <span class="pourquoi-carte__texte">{{ $carte->intro }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                @if ($s->secondaryImageUrl())
                    <div class="pourquoi-media pourquoi-media--droite">
                        <img src="{{ $s->secondaryImageUrl() }}" alt="" width="800" height="1200"
                             loading="lazy" decoding="async">
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
