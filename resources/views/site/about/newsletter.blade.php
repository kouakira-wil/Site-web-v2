{{--
    Inscription à la lettre d'information.

    Reprend les jetons du gabarit : conteneur Orisa, bouton `.at-btn` avec son
    survol à deux couches, filets `--sd3-line`. Aucune couleur nouvelle.
--}}
<section class="newsletter-area pt-60 pb-100" id="newsletter" aria-labelledby="titre-newsletter">
    <div class="container">
        <div class="newsletter-box">
            <div class="newsletter-box__intro">
                <p class="sd3-label mb-15">Lettre d’information</p>
                <h2 id="titre-newsletter" class="newsletter-box__title">Restez informé de la vie de l’école</h2>
                <p class="newsletter-box__lead">
                    Rentrées, portes ouvertes et actualités des filières, directement dans votre boîte mail.
                </p>
            </div>

            <div class="newsletter-box__form">
                @if (session('newsletter_status'))
                    <p class="newsletter-box__status" role="status">{{ session('newsletter_status') }}</p>
                @endif

                <form action="{{ route('newsletter.store') }}" method="POST" novalidate>
                    @csrf

                    <label for="newsletter-email" class="newsletter-box__label">Votre adresse e-mail</label>

                    <div class="newsletter-box__row">
                        <input id="newsletter-email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="prenom.nom@exemple.com"
                               autocomplete="email"
                               required
                               aria-describedby="newsletter-aide {{ $errors->has('email') ? 'newsletter-erreur' : '' }}"
                               @class(['newsletter-box__input', 'is-invalid' => $errors->has('email')])>

                        <button type="submit" class="at-btn !rounded-none">
                            <span><span class="text-1">S’inscrire</span><span class="text-2">S’inscrire</span></span>
                        </button>
                    </div>

                    {{-- Pot de miel : masqué aux personnes, rempli par les robots. --}}
                    <div class="newsletter-box__trap" aria-hidden="true">
                        <label for="newsletter-website">Ne pas remplir</label>
                        <input id="newsletter-website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    @error('email')
                        <p id="newsletter-erreur" class="newsletter-box__error" role="alert">{{ $message }}</p>
                    @enderror

                    <p id="newsletter-aide" class="newsletter-box__hint">
                        Une adresse suffit. Désinscription possible à tout moment en nous écrivant.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
