<section class="sd3-runway pt-40 pb-100" id="sd3-runway" data-sd3-runway>
                    <div class="container">
                        <div class="sd3-runway__layout">
                            <div class="sd3-runway__sticky">
                                <p class="sd3-label mb-15 at_fade_anim" data-fade-from="bottom">[02] Parcours</p>
                                <h2 class="sd3-runway__title reveal-text mb-20">Les cycles de cette filière</h2>
                                <p class="sd3-runway__text at_fade_anim" data-fade-from="bottom" data-delay=".1">
                                    Chaque cycle prolonge le précédent : on peut entrer à n’importe quel niveau et poursuivre sans rupture.
                                </p>
                                <div class="sd3-runway__meter" data-sd3-meter>
                                    <span data-sd3-meter-label>Cycle 01</span>
                                    <div class="sd3-runway__rail"><i data-sd3-meter-fill></i></div>
                                </div>
                            </div>
                            <div class="sd3-runway__steps">@forelse ($project->cycles as $cycle)
<article class="sd3-step" data-sd3-step>
                                    <span class="sd3-step__week">{{ $cycle->short_label }}</span>
                                    <h3 class="sd3-step__name">{{ $cycle->name }}</h3>
                                    <p>{{ $cycle->description }}</p>
                                </article>
@empty
<p>Les cycles de cette filière seront précisés prochainement.</p>
@endforelse</div>
                        </div>
                    </div>
                </section>

                <!-- SD3 — Offer tracks stack scroll -->
