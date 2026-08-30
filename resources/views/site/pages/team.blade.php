
                <!-- Team 02 / Hero — editorial roster intro -->
                <section class="team2-hero pt-150 pb-60 !overflow-hidden" data-team2-hero>
                    <div class="container">
                        <div class="team2-hero__top">
                            <nav class="team2-crumb at_fade_anim" data-fade-from="bottom" aria-label="Breadcrumb">
                                <a href="{{ route('home') }}">Accueil</a>
                                <span>/</span>
                                <span>Équipe</span>
                            </nav>
                            <span class="team2-hero__count at_fade_anim" data-fade-from="bottom" data-delay=".08">
                                <strong data-team2-count data-to="24">0</strong> people across craft &amp; ops
                            </span>
                        </div>

                        <div class="team2-hero__main">
                            <h1 class="section-title fw-500 fz-ds-1 !leading-none reveal-text">
                                Celles et ceux qui vous accompagnent
                            </h1>
                            <div class="team2-hero__aside">
                                <p class="fz-font-xl !mb-6 at_fade_anim" data-fade-from="bottom" data-delay=".18">
                                    Enseignants, encadrants d’atelier et personnel administratif : une équipe disponible, du premier entretien d’orientation jusqu’à l’insertion.
                                </p>
                                <div class="at-btn-group at_fade_anim" data-delay=".26" data-fade-from="bottom" data-ease="bounce">
                                    <a class="at-btn-circle" href="#team2-roster" aria-label="View team roster">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                            <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <a class="at-btn z-index-1" href="#team2-roster">Voir l’équipe</a>
                                    <a class="at-btn-circle" href="#team2-roster" aria-label="View team roster">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15" viewBox="0 0 16 15" fill="none">
                                            <path d="M0.0001297 8.99993L0 3.00407e-05L2 0L2.0001 6.99993L12.1719 7.00003L8.22224 3.05027L9.63644 1.63606L16.0003 8.00003L9.63644 14.364L8.22224 12.9497L12.1719 9.00003L0.0001297 8.99993Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <ul class="team2-hero__meta" data-team2-meta>
                            <li><span>Campus</span><strong>Brazzaville</strong></li>
                            <li><span>Pôles</span><strong>Technologie · Gestion</strong></li>
                            <li><span>Cycles</span><strong>CAP · BTS · Licence · Master</strong></li>
                        </ul>
                    </div>
                </section>
                <!-- Team 02 / Roster — list + sticky portrait -->
                <section class="team2-roster pt-40 pb-100" id="team2-roster" data-team2-roster aria-label="Team roster">
                    <div class="container">
                        <div class="team2-roster__layout">
                            <div class="team2-roster__preview" data-team2-preview>
                                <div class="team-card changeless">
                                    <div class="team-card-image">
                                        <div class="anim-zoomin">
                                            <img src="{{ asset('assets/imgs/pages/img-17.webp') }}" alt="Direction pédagogique" class="img-cover" data-team2-preview-img loading="eager" width="640" height="800">
                                        </div>
                                    </div>
                                    <a href="#" class="team-card-icon" aria-label="View profile">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
                                            <path d="M7.85986 2.43872L1.7123 8.58629L0.702148 7.57614L6.84971 1.42857H1.43131V0H9.28843V7.85714H7.85986V2.43872Z" fill="currentColor" />
                                        </svg>
                                    </a>
                                    <div class="team-card-content">
                                        <a href="#" class="team-card-name">
                                            <h2 class="h6 fz-font-2xl" data-team2-preview-name>Direction pédagogique</h2>
                                        </a>
                                        <p class="team-card-position fz-font-sm !m-0 common-white" data-team2-preview-role>Coordination des cycles</p>
                                    </div>
                                </div>
                            </div>

                            <div class="team2-roster__list-wrap">
                                <div class="team2-roster__head">
                                    <p class="team2-label !mb-0">Équipe</p>
                                    <span class="team2-roster__hint">Survolez un nom</span>
                                </div>

                                <ul class="team2-roster__list" data-team2-list>
                                    <li class="team2-person is-active" data-team2-person data-img="assets/imgs/pages/img-17.webp" data-name="Direction pédagogique" data-role="Coordination des cycles">
                                        <a class="team2-person__link" href="#">
                                            <span class="team2-person__index">01</span>
                                            <span class="team2-person__name">Direction pédagogique</span>
                                            <span class="team2-person__role">Coordination des cycles</span>
                                            <span class="team2-person__arrow" aria-hidden="true">
                                                <svg width="14" height="14" viewBox="0 0 10 10" fill="none">
                                                    <path d="M7.86 2.44L1.71 8.59L0.7 7.58l6.15-6.15H1.43V0h7.86v7.86H7.86V2.44z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="team2-person" data-team2-person data-img="assets/imgs/pages/img-18.webp" data-name="Pôle Technologie" data-role="Project Manager">
                                        <a class="team2-person__link" href="#">
                                            <span class="team2-person__index">02</span>
                                            <span class="team2-person__name">Pôle Technologie</span>
                                            <span class="team2-person__role">Réseaux et informatique</span>
                                            <span class="team2-person__arrow" aria-hidden="true">
                                                <svg width="14" height="14" viewBox="0 0 10 10" fill="none">
                                                    <path d="M7.86 2.44L1.71 8.59L0.7 7.58l6.15-6.15H1.43V0h7.86v7.86H7.86V2.44z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="team2-person" data-team2-person data-img="assets/imgs/pages/img-19.webp" data-name="Pôle Gestion" data-role="Comptabilité et finance">
                                        <a class="team2-person__link" href="#">
                                            <span class="team2-person__index">03</span>
                                            <span class="team2-person__name">Pôle Gestion</span>
                                            <span class="team2-person__role">Comptabilité et finance</span>
                                            <span class="team2-person__arrow" aria-hidden="true">
                                                <svg width="14" height="14" viewBox="0 0 10 10" fill="none">
                                                    <path d="M7.86 2.44L1.71 8.59L0.7 7.58l6.15-6.15H1.43V0h7.86v7.86H7.86V2.44z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="team2-person" data-team2-person data-img="assets/imgs/pages/img-20.webp" data-name="Service des admissions" data-role="Orientation et inscriptions">
                                        <a class="team2-person__link" href="#">
                                            <span class="team2-person__index">04</span>
                                            <span class="team2-person__name">Service des admissions</span>
                                            <span class="team2-person__role">Orientation et inscriptions</span>
                                            <span class="team2-person__arrow" aria-hidden="true">
                                                <svg width="14" height="14" viewBox="0 0 10 10" fill="none">
                                                    <path d="M7.86 2.44L1.71 8.59L0.7 7.58l6.15-6.15H1.43V0h7.86v7.86H7.86V2.44z" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Team 02 / Disciplines -->
                <section class="team2-disc pt-40 pb-100" data-team2-disc>
                    <div class="container">
                        <div class="team2-disc__head">
                            <p class="team2-label mb-15 at_fade_anim" data-fade-from="bottom">Notre organisation</p>
                            <h2 class="team2-disc__title reveal-text !mb-0">Des promotions à taille humaine, encadrées de près</h2>
                        </div>
                        <div class="team2-disc__grid">
                            <article class="team2-disc__card" data-team2-disc-card>
                                <span class="team2-disc__num">01</span>
                                <h3>Enseignement</h3>
                                <p>Cours magistraux et travaux dirigés, avec un suivi individuel des acquis.</p>
                            </article>
                            <article class="team2-disc__card" data-team2-disc-card>
                                <span class="team2-disc__num">02</span>
                                <h3>Ateliers</h3>
                                <p>Travaux pratiques sur équipements réels, encadrés par des professionnels.</p>
                            </article>
                            <article class="team2-disc__card" data-team2-disc-card>
                                <span class="team2-disc__num">03</span>
                                <h3>Stages</h3>
                                <p>Stages en entreprise et accompagnement à la recherche d’emploi.</p>
                            </article>
                            <article class="team2-disc__card" data-team2-disc-card>
                                <span class="team2-disc__num">04</span>
                                <h3>Administration</h3>
                                <p>Scolarité, admissions et vie étudiante au quotidien.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <!-- Team 02 / CTA -->
                <section class="team2-cta pt-40 pb-120" data-team2-cta>
                    <div class="container">
                        <div class="team2-cta__panel changeless" data-team2-cta-panel>
                            <div class="team2-cta__copy">
                                <p class="team2-label team2-label--light mb-15">Recrutement</p>
                                <h2 class="team2-cta__title text-scale-anim-2 mb-20">Vous souhaitez enseigner à l’ESCAT ?</h2>
                                <p class="team2-cta__text">Nous accueillons les professionnels souhaitant transmettre leur expérience à nos étudiants.</p>
                            </div>
                            <div class="team2-cta__actions">
                                <a class="at-btn" href="{{ route('contact') }}">
                                    <span><span class="text-1">Send a note</span><span class="text-2">Send a note</span></span>
                                    <i>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                        </svg>
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                            <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor" />
                                        </svg>
                                    </i>
                                </a>
                                <a class="team2-cta__mail" href="mailto:infos@escat.org">infos@escat.org</a>
                            </div>
                        </div>
                        <div class="team2-next">
                            <a href="#">Équipe</a>
                            <a href="#">Détails</a>
                            <a href="#">L’école</a>
                        </div>
                    </div>
                </section>
