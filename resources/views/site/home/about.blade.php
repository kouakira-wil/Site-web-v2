@php
    $s = \App\Models\PageHeader::forKey('home.about');

    $argument = \App\Models\PageHeader::query()
        ->where('key', 'like', 'home.about.%')
        ->orderBy('position')
        ->first();
@endphp

@if ($s)
    {{--
        Section « à propos » de l'accueil.

        Photo à gauche avec une carte posée dessus, discours à droite :
        pastille de surtitre, titre, accroche, citation encadrée, argument à
        pictogramme et bouton. Tout se règle au back-office.
    --}}
    <section class="apropos-area pt-120 pb-120" aria-labelledby="titre-apropos">
        <div class="container">
            <div class="apropos-grid">
                @if ($s->imageUrl())
                    <div class="apropos-media">
                        <img src="{{ $s->imageUrl() }}" alt="" width="900" height="900"
                             loading="lazy" decoding="async">

                        @if ($s->tagList() !== [])
                            <div class="apropos-carte">
                                <span class="apropos-carte__guillemet" aria-hidden="true">&ldquo;</span>
                                <p class="apropos-carte__texte">{{ $s->tagList()[0] }}</p>

                                @if (filled($s->cta_label))
                                    <a class="apropos-carte__lien" href="{{ $s->cta_url ?: route('about') }}">
                                        {{ $s->cta_label }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <div class="apropos-body">
                    @if (filled($s->eyebrow))
                        <p class="apropos-pastille">
                            @if (filled($s->icon))
                                <span class="apropos-pastille__icone" aria-hidden="true">
                                    <x-site.icon :name="$s->icon" class="w-4 h-4" />
                                </span>
                            @endif
                            {{ $s->eyebrow }}
                        </p>
                    @endif

                    <h2 id="titre-apropos" class="apropos-body__title reveal-text">{{ $s->title }}</h2>

                    @if (filled($s->intro))
                        <p class="apropos-body__lead">{{ $s->intro }}</p>
                    @endif

                    @if (filled($s->body))
                        <blockquote class="apropos-citation">
                            <p>{{ $s->body }}</p>
                        </blockquote>
                    @endif

                    @if ($argument && filled($argument->title))
                        <div class="apropos-argument">
                            @if (filled($argument->icon))
                                <span class="apropos-argument__icone" aria-hidden="true">
                                    <x-site.icon :name="$argument->icon" class="w-8 h-8" />
                                </span>
                            @endif

                            <span class="apropos-argument__corps">
                                <strong class="apropos-argument__titre">{{ $argument->title }}</strong>
                                @if (filled($argument->intro))
                                    <span class="apropos-argument__texte">{{ $argument->intro }}</span>
                                @endif
                            </span>
                        </div>
                    @endif

                    @if (filled($s->cta_label))
                        <div class="apropos-actions">
                            <a class="apropos-bouton" href="{{ $s->cta_url ?: route('about') }}">
                                {{ $s->cta_label }}
                                <span class="apropos-bouton__fleche" aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
