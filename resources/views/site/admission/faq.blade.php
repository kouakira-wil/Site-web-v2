@php
    $section = \App\Models\PageHeader::forKey('admission.faq');

    $questions = \App\Models\PageHeader::query()
        ->where('key', 'like', 'admission.faq.%')
        ->orderBy('position')
        ->get()
        ->filter(fn ($question): bool => filled($question->title))
        ->values();
@endphp

@if ($section && $questions->isNotEmpty())
    <section class="adm-section adm-section--gris" aria-labelledby="titre-faq">
        <div class="container">
            <header class="adm-tete">
                <h2 id="titre-faq" class="adm-tete__titre reveal-text">{{ $section->title }}</h2>

                @if (filled($section->intro))
                    <p class="adm-tete__intro">{{ $section->intro }}</p>
                @endif
            </header>

            <div class="adm-volets adm-volets--faq" id="accordeon-faq">
                @foreach ($questions as $question)
                    @php
                        // Identifiant distinct de celui des critères : les deux
                        // accordéons cohabitent sur la même page.
                        $panneau = 'faq-admission-'.$loop->iteration;
                        $ouvert = $loop->first;
                    @endphp

                    <div class="adm-volet">
                        <button class="adm-volet__bouton{{ $ouvert ? '' : ' collapsed' }}" type="button"
                                data-at-collapse data-at-collapse-target="#{{ $panneau }}"
                                aria-expanded="{{ $ouvert ? 'true' : 'false' }}"
                                aria-controls="{{ $panneau }}">
                            <span class="adm-volet__marque" aria-hidden="true">
                                <x-site.icon name="help" class="w-5 h-5" />
                            </span>

                            <span>{{ $question->title }}</span>

                            <span class="adm-volet__chevron" aria-hidden="true">
                                <svg width="14" height="9" viewBox="0 0 14 9" fill="none">
                                    <path d="M1 1L7 7L13 1" stroke="currentColor" stroke-width="1.8"
                                          stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </button>

                        <div id="{{ $panneau }}" class="at-collapse adm-volet__panneau{{ $ouvert ? ' is-open' : '' }}"
                             data-at-collapse-parent="#accordeon-faq">
                            <div class="adm-volet__corps">
                                @foreach (preg_split('/\R{2,}/', (string) $question->body, -1, PREG_SPLIT_NO_EMPTY) as $paragraphe)
                                    <p>{{ $paragraphe }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
