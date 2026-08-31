@php
    $s = \App\Models\PageHeader::forKey('home.process');
@endphp

<!-- at-sec9-area-start -->
                <div class="container-2200">
                    <div class="at-sec9-area pt-130 pb-130 !rounded-[2rem] lg:!mx-4 !mx-2 border-100 mt-10">
                        <div class="container">
                            <div class="flex flex-wrap -mx-6 [&>*]:px-6 gap-y-12">
                                <div class="w-full 2xl:w-7/12 lg:w-2/3">
                                    <div class="at-about-title-wrap mb-30">
                                        <span class="at-btn common-black !bg-transparent mb-10 !rounded-none !p-0">
                                            <span class="!uppercase">
                                                <span class="text-1">{{ $s?->eyebrow }}</span>
                                                <span class="text-2">{{ $s?->eyebrow }}</span>
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
                                        <h2 class="h3 fw-600 at-section-title reveal-text lg:!mb-0 !mb-6">
                                            {{ $s?->title }}</h2>
                                        <div class="at-btn-group at_fade_anim pt-30" data-delay=".4" data-fade-from="bottom" data-ease="bounce">
                                            <a class="at-btn-circle" href="{{ route('contact') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                                </svg>
                                            </a>
                                            <a class="at-btn z-index-1" href="{{ route('contact') }}">Rejoindre l’équipe</a>
                                            <a class="at-btn-circle" href="{{ route('contact') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                    <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="at-about-content pt-120">
                                            <div class="!flex !gap-6 !justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                                    <path d="M20 40V20H0L20 0L40 20V40H20Z" fill="currentColor" />
                                                    <path d="M0 20L20 40H0V20Z" fill="currentColor" />
                                                </svg>
                                                <div>
                                                    <h3 class="h6 fw-600">Nous trouver</h3>
                                                    <div class="!flex md:!flex-row !flex-col md:!gap-12 !gap-6">
                                                        <span class="fz-font-md neutral-500">
                                                            Brazzaville
                                                            <br class="!block">
                                                            Brazzaville, République du Congo
                                                            <br class="!block">
                                                            Téléphone : <span class="neutral-900"><a href="tel:+242068848344">+242 06 884 83 44</a></span>
                                                            <br class="!block">
                                                            E-mail : <span class="neutral-900"><a href="mailto:infos@escat.org">infos@escat.org</a></span>
                                                        </span>
                                                        <span class="fz-font-md neutral-500 md:!pl-12">
                                                            Service des admissions
                                                            <br class="!block">
                                                            Brazzaville, République du Congo
                                                            <br class="!block">
                                                            Téléphone : <span class="neutral-900"><a href="tel:+242068848344">+242 06 884 83 44</a></span>
                                                            <br class="!block">
                                                            E-mail : <span class="neutral-900"><a href="mailto:infos@escat.org">infos@escat.org</a></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             <!-- beautify ignore:end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                <!-- at-sec9-area-end -->
