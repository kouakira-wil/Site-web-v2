@php
    $s = \App\Models\PageHeader::forKey('home.about');
@endphp

<!-- at-about-area-start -->
<div class="at-about-area pt-100">
    <div class="container">
        <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3">
            <div class="w-full 2xl:w-5/6 col-xxl-10">
                <div class="at-about-title-wrap mb-30">
                    <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                        <span class="!uppercase">
                            <span class="text-1">{{ $s?->eyebrow }}</span>
                            <span class="text-2">{{ $s?->eyebrow }}</span>
                        </span>
                    </span>
                    <h3 class="at-section-title !mb-0">
                        <span> {{ $s?->title }}</span>
                        <span class="at-about-btn-transform ml-20">
                            <a class="at-btn" href="{{ route('contact') }}">
                                <span>
                                    <span class="text-1">{{ $s?->cta_label }}</span>
                                    <span class="text-2">{{ $s?->cta_label }}</span>
                                </span>
                            </a>
                        </span>
                    </h3>
                </div>
            </div>
        </div>
        <div class="at-about-border mt-20 pt-55">
            <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                <div class="w-full 2xl:w-1/6 lg:w-1/4 md:w-7/12 md:!mb-12">
                    <div class="at-about-subtitle-wrap mb-30">
                        <span class="at-about-subtitle">
                            <br class="!block">
                            Nous formons des profils <span class="fw-700">solides</span>, prêts à prendre leur place <br> dans les entreprises du Congo.
                        </span>
                    </div>
                </div>
                <div class="lg:w-1/12 md:w-1/4 col-md-3 w-5/12 !self-end !ml-auto">
                    <div class="at-about-svg-wrap move-up">
                        <svg xmlns="http://www.w3.org/2000/svg" width="57" height="91" viewBox="0 0 57 91" fill="none">
                            <path opacity="0.1" d="M0 0L56.4024 33.572V90.336L0 56.46V0Z" fill="#515151" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="57" height="91" viewBox="0 0 57 91" fill="none">
                            <path opacity="0.2" d="M56.4009 0L8.7738e-05 33.5367V90.2413L56.4009 56.4008V0Z" fill="#515151" />
                        </svg>
                    </div>
                </div>
                <div class="w-full lg:w-2/3 !ml-auto">
                    <div class="at-about-thumb-wrap ml-75">
                        <div class="gx-80 flex flex-wrap -mx-[15px] [&>*]:px-[15px] 2xl:-mx-[40px] 2xl:[&>*]:px-[40px]">
                            <div class="w-full lg:w-1/2 md:w-1/2">
                                <div class="at-about-item anim-zoomin-wrap mb-40">
                                    <div class="mb-35">
                                        <div class="at-about-thumb fix anim-zoomin">
                                            <img data-speed=".8" src="{{ asset('assets/imgs/pages/img-3.jpeg') }}" alt="ESCAT">
                                        </div>
                                    </div>
                                    <div class="at-about-content">
                                        <h3 class="at-about-title mb-10 at-char-animation">Excellence académique</h3>
                                        <p class="at-about-dec at_fade_anim">Des programmes exigeants, révisés avec les
                                            entreprises du secteur, pour que chaque diplômé sorte avec des compétences réellement
                                            attendues sur le marché.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-1/2 md:w-1/2">
                                <div class="at-about-item mb-40 !flex !flex-col !gap-6">
                                    <div class="at-about-content !order-2 md:!order-1">
                                        <h3 class="at-about-title mb-10 at-char-animation">Insertion professionnelle</h3>
                                        <p class="at-about-dec at_fade_anim">Ateliers pratiques, stages encadrés et réseau
                                            d’anciens : nos diplômés affichent un taux d’emploi supérieur à la moyenne nationale,
                                            dans la technologie comme dans la gestion.</p>
                                    </div>
                                    <div class="anim-zoomin-wrap !order-1 md:!order-2">
                                        <div class="at-about-thumb fix anim-zoomin">
                                            <img data-speed=".8" src="{{ asset('assets/imgs/pages/img-4.jpeg') }}" alt="ESCAT">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- at-about-area-end -->
