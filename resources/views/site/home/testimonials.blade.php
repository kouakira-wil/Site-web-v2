@php
    $s = \App\Models\PageHeader::forKey('home.testimonials');
@endphp

<!-- at-sec7-area-start -->
                <div class="container-2200">
                    <div class="at-sec7-area pt-120 pb-120 bg-neutral-50 !rounded-[2rem] lg:!mx-4 !mx-2 mt-10">
                        <div class="container">
                            <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3">
                                <div class="w-full 2xl:w-2/3 xl:w-full">
                                    <div class="at-about-title-wrap mb-30">
                                        <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                                            <span class="!uppercase">
                                                <span class="text-1">{{ $s?->eyebrow }}</span>
                                                <span class="text-2">{{ $s?->eyebrow }}</span>
                                            </span>
                                        </span>
                                        <h3 class="h3 fw-600 at-section-title reveal-text mb-80">
                                            {{ $s?->title }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3 gap-y-6">
                                <div class="lg:w-1/3 md:w-3/4 w-full">
                                    <div class="!flex !flex-col !gap-2">
                                        <div class="at-image-hover p-relative !rounded-2xl !overflow-hidden">
                                            <div class="anim-zoomin">
                                                <img class="zoom-blur-image img-cover" src="{{ asset('assets/imgs/pages/img-11.jpg') }}" alt="ESCAT">
                                            </div>
                                            <img class="p-absolute !bottom-0 !left-0 !m-6" src="{{ asset('assets/imgs/template/logo/logo-w-lg.png') }}" alt="ESCAT">
                                            <div class="content !absolute !top-0 !right-0 !m-6">
                                                <a class="!bg-transparent !uppercase !border !px-4 !py-1 !rounded-full !text-white fz-font-label" href="{{ route('projects.index') }}">Accompagnement</a>
                                            </div>
                                        </div>
                                        <div class="at-image-hover p-relative !rounded-2xl !overflow-hidden">
                                            <div class="anim-zoomin">
                                                <img class="zoom-blur-image img-cover" src="{{ asset('assets/imgs/pages/img-132.jpg') }}" alt="ESCAT">
                                            </div>
                                            <div class="content">
                                                <h4 class="h6 common-white !absolute !bottom-0 !left-0 !m-6">Un projet d’orientation se construit à deux. Parlons-en.</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full 2xl:w-1/2 lg:w-2/3 !ml-auto">
                                    <div class="flex flex-wrap -mx-1 [&>*]:px-1 gap-y-2">
                                        <div class="lg:w-7/12 md:w-2/3 w-full">
                                            <h4 class="h6 reveal-text neutral-800 mb-60">L’ESCAT va au-delà des cours magistraux : ateliers pratiques, projets encadrés et suivi personnalisé pour que chacun avance à son rythme, sans jamais rester seul face à une difficulté.</h4>
                                        </div>
                                        <div class="md:w-1/2 w-full">
                                            <div class="hover-unborder">
                                                <div class="bg-neutral-0 !rounded-2xl !px-12 !py-4">
                                                    <h4 class="!flex !justify-between !items-center !mb-0">
                                                        <span>4</span>
                                                        <span>+</span>
                                                    </h4>
                                                </div>
                                                <div class="bg-neutral-0 !rounded-2xl !p-12 !mt-2">
                                                    <h5 class="h6 !text-right !mb-0">
                                                        Active <br>
                                                        du CAP au Master
                                                    </h5>
                                                    <div class="pt-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M30 20H20V30L20 40H10L0 30L10 20H0V0H10L20 10V1.90735e-06L30 0L40 10L30 20ZM20 10V20H10L20 10Z" fill="currentColor" />
                                                            <path d="M30 20H40V40H30L20 30L30 20Z" fill="currentColor" />
                                                        </svg>
                                                        <p class="fz-font-lg !mt-4 !mb-0">Un parcours continu, du CAP au Master, sans rupture ni réorientation subie</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="md:w-1/2 w-full">
                                            <div class="hover-unborder">
                                                <div class="bg-neutral-0 !rounded-2xl !p-12 !mb-2">
                                                    <h5 class="h6 !text-right !mb-0">
                                                        Trusted
                                                        <br>
                                                        Partners

                                                    </h5>
                                                    <div class="pt-150">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                                                            <path d="M38 12L31 19L19 7L26 0H38V12Z" fill="currentColor" />
                                                            <path d="M7 19L19 7L12 0H0V12L7 19Z" fill="currentColor" />
                                                            <path d="M19 31L12 38H0V26L7 19V31H19Z" fill="currentColor" />
                                                            <path d="M19 31H31V19L38 26V38H26L19 31Z" fill="currentColor" />
                                                        </svg>
                                                        <p class="fz-font-lg !mt-4 !mb-0">Des entreprises et institutions qui accueillent nos étudiants en stage et recrutent nos diplômés.</p>
                                                    </div>
                                                </div>
                                                <div class="bg-neutral-0 !rounded-2xl !px-12 !py-4">
                                                    <h4 class="!flex !justify-between !items-center !mb-0">
                                                        <span>2</span>
                                                        <span>+</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- at-sec7-area-end -->
