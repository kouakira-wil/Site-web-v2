<section class="sd3-hero !overflow-hidden" data-sd3-hero>
                    <div class="sd3-hero__stage">
                        <div class="sd3-hero__media" data-sd3-hero-media>
                            <img src="{{ $project->coverUrl() ?? asset('assets/imgs/pages/img-107.webp') }}"
                                 alt="{{ $project->coverUrl() ? 'Filière '.$project->title : '' }}"
                                 width="1600" height="1000" loading="eager">
                            <div class="sd3-hero__veil" aria-hidden="true"></div>
                        </div>

                        <div class="container p-relative !z-10">
                            <div class="sd3-hero__chrome">
                                <nav class="sd3-crumb at_fade_anim" data-fade-from="bottom" aria-label="Breadcrumb">
                                    <a href="{{ route('projects.index') }}">Filières</a>
                                    <span>/</span>
                                    <span>{{ $project->title }}</span>
                                </nav>
                                <div class="sd3-hero__clock at_fade_anim" data-fade-from="bottom" data-delay=".08">
                                    <span class="sd3-hero__clock-label">Pôle</span>
                                    <strong>{{ $project->category?->name ?? '—' }}</strong>
                                </div>
                            </div>

                            <div class="sd3-hero__core">
                                <p class="sd3-hero__kicker at_fade_anim" data-fade-from="bottom" data-delay=".1">Filière</p>
                                <h1 class="sd3-hero__title">
                                    <span class="sd3-hero__line" data-sd3-title-line>{{ $project->title }}</span>
                                    <span class="sd3-hero__line sd3-hero__line--accent" data-sd3-title-line>{{ $project->category?->name }}</span>
                                </h1>
                                <div class="sd3-hero__bar">
                                    <p class="sd3-hero__lead at_fade_anim" data-fade-from="bottom" data-delay=".2">
                                        {{ $project->excerpt }}
                                    </p>
                                    <div class="sd3-hero__actions at_fade_anim" data-fade-from="bottom" data-delay=".28">
                                        <a class="at-btn !rounded-none" href="{{ route('contact') }}">
                                            <span><span class="text-1">Demander une préinscription</span><span class="text-2">Demander une préinscription</span></span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                                </svg>
                                            </i>
                                        </a>
                                        <a class="sd3-hero__scroll" href="#sd3-runway">Le parcours</a>
                                    </div>
                                </div>
                            </div>

                            <ul class="sd3-hero__tags" data-sd3-tags aria-label="Cycles ouverts">
                                @forelse ($project->cycles as $cycle)
                                    <li data-sd3-tag>{{ $cycle->name }}</li>
                                @empty
                                    <li data-sd3-tag>Cycle à préciser</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="sd3-hero__rail" aria-hidden="true" data-sd3-rail>
                            <span>{{ $project->cycles->count() }} cycle(s)</span>
                            <span class="sd3-hero__rail-line"></span>
                            <span>{{ $project->category?->name }}</span>
                        </div>
                    </div>
                </section>

                <!-- SD3 — Outcomes as typographic measure strip -->
