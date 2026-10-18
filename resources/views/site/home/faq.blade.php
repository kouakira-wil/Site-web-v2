@php
    $s = \App\Models\PageHeader::forKey('home.faq');
    $carte = \App\Models\PageHeader::forKey('home.faq.contact');

    // Les questions, dans l'ordre : home.faq.1, .2, … La carte de contact
    // partage le préfixe mais tient sa propre colonne.
    $questions = \App\Models\PageHeader::query()
        ->where('key', 'like', 'home.faq.%')
        ->where('key', '!=', 'home.faq.contact')
        ->orderBy('position')
        ->get()
        ->filter(fn ($question): bool => filled($question->title))
        ->values();
@endphp

@if ($s)
<!-- alt-faq-area-start -->
                <div class="alt-faq-area pt-145 pb-80 ">
                    <div class="container">
                        <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full lg:w-5/12">
                                <div class="alt-faq-title-wrap mb-40">
                                    <div class="!rounded-2xl !overflow-hidden anim-zoomin">
                                        <img src="{{ $s->imageUrl() ?? asset('assets/imgs/pages/img-21.jpg') }}" data-speed=".8" class="!w-full" alt="ESCAT">
                                    </div>

                                    @if ($carte)
                                        <h3 class="h6 fw-600 mb-15 pt-50">{{ $carte->title }}</h3>

                                        @if (filled($carte->intro))
                                            <p class="at-faq-dec mb-35">{{ $carte->intro }}</p>
                                        @endif

                                        @if (filled($carte->cta_label))
                                            @php $lienCarte = $carte->cta_url ?: route('contact'); @endphp
                                            <div class="at-btn-group at_fade_anim" data-delay=".4" data-fade-from="bottom" data-ease="bounce">
                                                <a class="at-btn-circle" href="{{ $lienCarte }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                        <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                                    </svg>
                                                </a>
                                                <a class="at-btn z-index-1" href="{{ $lienCarte }}">{{ $carte->cta_label }}</a>
                                                <a class="at-btn-circle" href="{{ $lienCarte }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                        <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="w-full lg:w-7/12">
                                <div class="at-faq ml-115">
                                    @if (filled($s->eyebrow))
                                    <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                                        <span class="!uppercase">
                                            <span class="text-1">{{ $s->eyebrow }}</span>
                                            <span class="text-2">{{ $s->eyebrow }}</span>
                                        </span>
                                        <i>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                            </svg>
                                        </i>
                                    </span>
                                    @endif

                                    <h3 class="at-section-title fw-600 reveal-text">
                                        {{ $s->title }}
                                    </h3>

                                    @if ($questions->isNotEmpty())
                                    <div class="pt-80" id="accordionExample">
                                        @foreach ($questions as $question)
                                            @php
                                                // Un identifiant par question : le gabarit s'en sert
                                                // pour relier le bouton au panneau qu'il déplie.
                                                $panneau = 'faq-reponse-'.$loop->iteration;
                                                $ouvert = $loop->first;
                                            @endphp

                                            <div class="at-faq-item scroll-move-up !rounded-2xl">
                                                <div class="at-faq-header !flex !gap-2">
                                                    <div class="box-number">
                                                        <span class="at-faq-number">{{ $loop->iteration }}</span>
                                                    </div>
                                                    <button class="at-faq-button{{ $ouvert ? '' : ' collapsed' }}" type="button"
                                                            data-at-collapse data-at-collapse-target="#{{ $panneau }}"
                                                            aria-expanded="{{ $ouvert ? 'true' : 'false' }}"
                                                            aria-controls="{{ $panneau }}">{{ $question->title }}</button>
                                                </div>
                                                <div id="{{ $panneau }}" class="at-faq-collapse at-collapse{{ $ouvert ? ' is-open' : '' }}"
                                                     data-at-collapse-parent="#accordionExample">
                                                    <div class="at-faq-body">
                                                        <p>{{ $question->body }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- alt-faq-area-end -->
@endif
