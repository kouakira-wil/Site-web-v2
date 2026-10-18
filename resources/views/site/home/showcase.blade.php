@php
    /*
     * Swiper n'accepte de boucler qu'à partir de « slidesPerView + fiches
     * tampons » vignettes, soit cinq avec les trois colonnes du bureau et le
     * centrage du gabarit. En dessous, il désactive la boucle : le ruban
     * s'arrête sur la dernière fiche et les flèches ne répondent plus. On
     * répète donc la liste jusqu'à six vignettes.
     */
    $minimumPourBoucler = 6;

    $base = \App\Models\Testimonial::active()->ordered()->get();
    $temoignages = $base;

    while ($base->isNotEmpty() && $temoignages->count() < $minimumPourBoucler) {
        $temoignages = $temoignages->concat($base);
    }
@endphp

@php
    $s = \App\Models\PageHeader::forKey('home.showcase');
    @endphp

<!--Home 1 Section 6-->
<div class="container-2200">
    <div class="sec-6-home-1 lg:!mx-4 !mx-2 !bg-black pt-100 pb-100 !rounded-[2rem] changeless !overflow-hidden">
        <div class="container">
            <div class="!items-end mb-50 z-index-2 flex flex-wrap -mx-2 [&>*]:px-2 gap-y-4">
                <div class="w-full md:w-2/3">
                    <h2 class="alt-section-title !leading-none mb-10 reveal-text !text-white">{{ $s?->eyebrow }}</h2>
                    <p class="mg-portfolio-dec mb-50 !text-white !mb-0">{{ $s?->title }}
                    </p>
                </div>
                <div class="w-full lg:w-1/6 !ml-auto">
                    <div class="swiper-button-wrapper !justify-end">
                        <button class="swiper-btn-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                <path d="M11.3481 7.47314L5.25879 13.2856L11.3481 19.0981" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21.3124 13.2856H5.53564" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <button class="swiper-btn-next">
                            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                <path d="M15.2234 7.47314L21.3126 13.2856L15.2234 19.0981" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.259 13.2856H21.0358" stroke="currentColor" stroke-width="1.66071" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                <div class="w-full scroll-move-up2 z-index-2">
                    <div class="swiper slider-testimonial">
                        <div class="swiper-wrapper">
                            @foreach ($temoignages as $t)
                            <div class="swiper-slide">
                                <div class="testimonial-cart-wrap 2xl:!p-8 lg:!p-6 md:!p-6 !p-5">
                                    <div class="rectangular"></div>
                                    <div class="testimonial-top !flex !items-center !justify-between">
                                        <div class="testimonial-top-left-img">
                                            @if ($t->photoUrl())
                                            <img src="{{ $t->photoUrl() }}" alt="{{ $t->signature() }}" loading="lazy" decoding="async">
                                            @else
                                            <span class="testimonial-initiale" aria-hidden="true">{{ $t->initial() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="testimonial-bottom-wrap">
                                        <div class="testimonial-content">
                                            <p class="testimonial-content-text neutral-0 fz-font-xl fw-400 text-truncate-4">{{ $t->formattedQuote() }}</p>
                                            <div class="testimonial-content-author">
                                                <h3 class="h6 testimonial-content-author-name common-white fw-600">{{ $t->signature() }}</h3>
                                                <p class="testimonial-content-author-position !m-0">{{ $t->program }}</p>
                                                <p class="testimonial-content-author-company !m-0">{{ $t->location }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <!-- beautify ignore:end -->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
