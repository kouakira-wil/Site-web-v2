@php
    $s = \App\Models\PageHeader::forKey('home.portfolio');
@endphp

<!-- mg-portfolio-area-start -->
                <div class="mg-portfolio-area pt-145 pb-65">
                    <div class="container">
                        <div class="flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full 2xl:w-1/3 lg:w-1/3">
                                <div class="mg-portfolio-title-wrap mg-portfolio-pin mb-30">
                                    <h2 class="alt-section-title !leading-none mb-30 reveal-text">{{ $s?->title }}</h2>
                                    <div class="at_fade_anim" data-delay=".3">
                                        <p class="mg-portfolio-dec mb-50">Quatre parcours construits avec les entreprises du secteur,
                                            où la théorie se vérifie en atelier dès la première année.</p>
                                    </div>
                                    <div class="at-btn-group at_fade_anim" data-delay=".4" data-fade-from="bottom" data-ease="bounce">
                                        <a class="at-btn-circle" href="{{ route('projects.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                            </svg>
                                        </a>
                                        <a class="at-btn z-index-1" href="{{ route('projects.index') }}">{{ $s?->cta_label }}</a>
                                        <a class="at-btn-circle" href="{{ route('projects.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full lg:w-7/12 !ml-auto">
                                <div class="mg-portfolio-item-wrap ml-130 mb-40">
                                    <!-- beautify ignore:start -->
                    @forelse ($featured as $filiere)
    <x-site.filiere-item :filiere="$filiere"
        :fallback="asset('assets/imgs/pages/img-'.(11 + ($loop->index % 4)).'.jpg')" />
@empty
    <p>Aucune filière mise en avant pour le moment.</p>
@endforelse

                    <!-- beautify ignore:end -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- mg-portfolio-area-end -->
