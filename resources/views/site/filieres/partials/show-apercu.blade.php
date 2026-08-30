<section class="sd3-out" data-sd3-out aria-label="Repères de la filière">
                    <div class="container">
                        <div class="sd3-out__strip" data-sd3-out-grid>
                            <div class="sd3-out__intro">
                                <span class="sd3-out__eyebrow">En bref</span>
                                <h2 class="sd3-out__title">{{ $project->category?->name ?? 'Filière' }}</h2>
                            </div>

                            <ul class="sd3-out__metrics">
                                <li class="sd3-out__metric" data-sd3-out-card>
                                    <span class="sd3-out__num" data-sd3-count data-to="{{ $project->cycles->count() }}">0</span>
                                    <span class="sd3-out__unit">cycle{{ $project->cycles->count() > 1 ? 's' : '' }} ouvert{{ $project->cycles->count() > 1 ? 's' : '' }}</span>
                                </li>
                                <li class="sd3-out__metric" data-sd3-out-card>
                                    <span class="sd3-out__num" data-sd3-count data-to="{{ $poleCount }}">0</span>
                                    <span class="sd3-out__unit">filière{{ $poleCount > 1 ? 's' : '' }} dans le pôle</span>
                                </li>
                                <li class="sd3-out__metric sd3-out__metric--accent" data-sd3-out-card>
                                    <span class="sd3-out__num">{{ $project->cycles->last()?->short_label ?? '—' }}</span>
                                    <span class="sd3-out__unit">niveau le plus élevé</span>
                                </li>
                            </ul>
                        </div>

                        <div class="sd3-out__track" data-sd3-out-track aria-hidden="true">
                            <i data-sd3-out-progress></i>
                        </div>
                    </div>
                </section>

                <!-- SD3 — Runway timeline (sticky + progress line) -->
