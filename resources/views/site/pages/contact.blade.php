@php
    // Bande d'images du bas : des photos de l'école, pas de visuels de gabarit.
    $bande = ['img-3.jpeg', 'img-11.jpg', 'img-12.jpg', 'img-13.jpg', 'img-14.jpg', 'img-21.jpg', 'img-5.jpg'];
@endphp

                <!-- Contact section 1 -->
                <div class="sec-1-contact !overflow-hidden pt-120">
                    <div class="container">
                        @if (session('status'))
                            {{-- Le formulaire renvoie ici après envoi : la confirmation
                                 est annoncée aux lecteurs d'écran dès son apparition. --}}
                            <div class="contact-avis" role="status">{{ session('status') }}</div>
                        @endif

                        <div class="!items-end flex flex-wrap -mx-3 [&>*]:px-3">
                            <div class="w-full 2xl:w-1/2 lg:w-7/12">
                                <div class="at-about-title-wrap mb-30">
                                    <div class="at-about-content !flex md:!flex-row !flex-col !justify-between !gap-6 pt-40">
                                        <div class="!flex !gap-6 w-lg-50">
                                            <div class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                                    <path d="M20 40V20H0L20 0L40 20V40H20Z" fill="currentColor" />
                                                    <path d="M0 20L20 40H0V20Z" fill="currentColor" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="h6 fw-600">Nous joindre</h3>
                                                <span class="fz-font-md neutral-500">
                                                    {{ config('ecole.ville') }}, {{ config('ecole.pays') }}
                                                    <br class="!block">
                                                    Téléphone : <span class="neutral-900"><a href="tel:{{ config('ecole.telephone_lien') }}">{{ config('ecole.telephone') }}</a></span>
                                                    <br class="!block">
                                                    E-mail : <span class="neutral-900"><a href="mailto:{{ config('ecole.email') }}">{{ config('ecole.email') }}</a></span>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Le second bloc répétait mot pour mot le premier :
                                             il porte désormais ce qu'il est seul à dire. --}}
                                        <div class="!flex !gap-6 w-lg-50">
                                            <div class="icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                                    <path d="M20 20V10L30 0H40V10L30 20H20Z" fill="currentColor" />
                                                    <path d="M20 30V20H10L20 10L10 0H0V20H10L0 30V40H10L20 30Z" fill="currentColor" />
                                                    <path d="M20 30L30 40H40V20H30L20 30Z" fill="currentColor" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="h6 fw-600">Horaires d’accueil</h3>
                                                <span class="fz-font-md neutral-500">
                                                    {{ config('ecole.jours') }}
                                                    <br class="!block">
                                                    <span class="neutral-900">{{ config('ecole.horaires') }}</span>
                                                    <br class="!block">
                                                    Le service des admissions reçoit sans rendez-vous.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full 2xl:w-1/3 lg:w-5/12 md:w-2/3 2xl:!ml-auto !relative">
                                <div class="scroll-rotate md:!inline-block !hidden">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M53.5715 0H46.4286V41.3778L17.17 12.1193L12.1193 17.17L41.3778 46.4286H0V53.5715H41.3778L12.1193 82.83L17.17 87.8805L46.4286 58.622V100H53.5715V58.622L82.83 87.8805L87.8805 82.83L58.622 53.5715H100V46.4286H58.622L87.8805 17.17L82.83 12.1193L53.5715 41.3778V0Z" fill="currentColor" />
                                        </svg>
                                    </div>
                                </div>

                                <h4 class="h6 !mb-6 fz-font-lg">
                                    Décrivez votre projet d’études en quelques lignes.
                                    Le service des admissions vous répond sous {{ config('ecole.delai_reponse') }}.
                                </h4>
                            </div>
                        </div>

                        <div class="pt-120 !items-start flex flex-wrap -mx-6 [&>*]:px-6 gap-y-12">
                            <div class="w-full 2xl:w-1/2 lg:w-7/12">
                                <h4>Écrivez-nous</h4>

                                {{--
                                    Le formulaire visait « # » : il ne partait nulle part,
                                    alors que la route POST /contact existe, valide, range
                                    le message et prévient l'équipe.
                                --}}
                                <form class="sec-4-about-form" action="{{ route('contact.store') }}" method="post"
                                      data-contact novalidate>
                                    @csrf

                                    {{--
                                        `novalidate` coupe les bulles du navigateur, en anglais
                                        et hors de la charte. `initValidationContact()` prend le
                                        relais et reprend mot pour mot les règles du serveur,
                                        qui reste seul juge : le script peut ne pas tourner.
                                    --}}
                                    <p class="contact-resume" data-contact-resume role="alert" hidden></p>

                                    <div class="sec-4-about-form__field">
                                        <label class="sr-only" for="contact-nom">Nom</label>
                                        <input type="text" id="contact-nom" class="sec-4-about-form__input" name="name"
                                               value="{{ old('name') }}" placeholder="Votre nom *"
                                               required maxlength="120" autocomplete="name"
                                               aria-describedby="erreur-nom"
                                               @error('name') aria-invalid="true" @enderror>
                                        <p class="contact-erreur" id="erreur-nom" data-erreur="name"
                                           @unless ($errors->has('name')) hidden @endunless>{{ $errors->first('name') }}</p>
                                    </div>

                                    <div class="sec-4-about-form__field">
                                        <label class="sr-only" for="contact-email">Adresse e-mail</label>
                                        <input type="email" id="contact-email" class="sec-4-about-form__input" name="email"
                                               value="{{ old('email') }}" placeholder="Votre adresse e-mail *"
                                               required maxlength="180" autocomplete="email"
                                               aria-describedby="erreur-email"
                                               @error('email') aria-invalid="true" @enderror>
                                        <p class="contact-erreur" id="erreur-email" data-erreur="email"
                                           @unless ($errors->has('email')) hidden @endunless>{{ $errors->first('email') }}</p>
                                    </div>

                                    <div class="sec-4-about-form__field">
                                        <label class="sr-only" for="contact-telephone">Téléphone</label>
                                        <input type="tel" id="contact-telephone" class="sec-4-about-form__input" name="phone"
                                               value="{{ old('phone') }}" placeholder="Votre téléphone"
                                               maxlength="40" autocomplete="tel"
                                               aria-describedby="erreur-telephone"
                                               @error('phone') aria-invalid="true" @enderror>
                                        <p class="contact-erreur" id="erreur-telephone" data-erreur="phone"
                                           @unless ($errors->has('phone')) hidden @endunless>{{ $errors->first('phone') }}</p>
                                    </div>

                                    <div class="sec-4-about-form__field">
                                        <label class="sr-only" for="contact-sujet">Sujet</label>
                                        <input type="text" id="contact-sujet" class="sec-4-about-form__input" name="subject"
                                               value="{{ old('subject') }}" placeholder="Sujet de votre demande"
                                               maxlength="180"
                                               aria-describedby="erreur-sujet"
                                               @error('subject') aria-invalid="true" @enderror>
                                        <p class="contact-erreur" id="erreur-sujet" data-erreur="subject"
                                           @unless ($errors->has('subject')) hidden @endunless>{{ $errors->first('subject') }}</p>
                                    </div>

                                    <div class="sec-4-about-form__field">
                                        <label class="sr-only" for="contact-message">Message</label>
                                        <textarea id="contact-message" class="sec-4-about-form__input sec-4-about-form__textarea"
                                                  name="message" rows="5" placeholder="Votre message *"
                                                  required minlength="20" maxlength="5000"
                                                  aria-describedby="aide-message erreur-message"
                                                  @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>

                                        {{-- Le minimum est annoncé avant la saisie, pas après :
                                             c'est la règle qui bloque le plus souvent l'envoi. --}}
                                        <p class="contact-aide" id="aide-message" data-contact-compteur>20 caractères minimum.</p>

                                        <p class="contact-erreur" id="erreur-message" data-erreur="message"
                                           @unless ($errors->has('message')) hidden @endunless>{{ $errors->first('message') }}</p>
                                    </div>

                                    {{-- Piège à robots : un navigateur laisse ce champ vide.
                                         Masqué à l'œil comme au lecteur d'écran. --}}
                                    <div class="contact-piege" aria-hidden="true">
                                        <label for="contact-website">Ne rien saisir</label>
                                        <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
                                    </div>

                                    <div class="sec-4-about-form__actions">
                                        <button type="submit" class="sec-4-about-form__btn at-btn">
                                            <span>
                                                <span class="text-1 !capitalize">Envoyer le message</span>
                                                <span class="text-2 !capitalize">Envoyer le message</span>
                                            </span>
                                            <i>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                                </svg>
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.21967 9.40717C-0.0732232 9.70006 -0.0732232 10.1749 0.21967 10.4678C0.512563 10.7607 0.987437 10.7607 1.28033 10.4678L0.21967 9.40717ZM10.6875 0.75C10.6875 0.335786 10.3517 2.97145e-09 9.9375 1.50485e-07L3.1875 -2.70983e-07C2.77329 -2.70983e-07 2.4375 0.335786 2.4375 0.75C2.4375 1.16421 2.77329 1.5 3.1875 1.5H9.1875V7.5C9.1875 7.91421 9.52329 8.25 9.9375 8.25C10.3517 8.25 10.6875 7.91421 10.6875 7.5L10.6875 0.75ZM0.75 9.9375L1.28033 10.4678L10.4678 1.28033L9.9375 0.75L9.40717 0.21967L0.21967 9.40717L0.75 9.9375Z" fill="currentColor"></path>
                                                </svg>
                                            </i>
                                        </button>
                                    </div>

                                    <p class="sec-4-about-form__disclaimer">
                                        En envoyant ce formulaire, vous acceptez que l’ESCAT traite vos données pour répondre à votre demande.
                                    </p>
                                </form>
                            </div>

                            <div class="w-full 2xl:w-1/3 lg:w-5/12 !ml-auto pb-40">
                                <h5 class="h6 fw-600 !mb-4">Nous suivre</h5>

                                <div class="alt-footer-social-item white-mode">
                                    <ul>
                                        <x-site.reseaux style="detaille" />
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bande d'images -->
                <div class="sec-4-about pt-120">
                    <div class="moving-gallery at_fade_anim carouselTicker carouselTicker-left" data-delay=".5" data-fade-from="bottom" data-ease="bounce">
                        <ul class="wrapper-gallery carouselTicker__list scroll-move-left">
                            @foreach ($bande as $photo)
                                <li>
                                    <img decoding="async" loading="lazy" src="{{ asset('assets/imgs/pages/'.$photo) }}" alt="">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
