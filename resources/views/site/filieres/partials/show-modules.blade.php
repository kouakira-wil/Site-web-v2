@php
    $blocs = $project->learningBlocks();
@endphp

@if ($blocs !== [])
    <section class="sd3-tracks pt-20 pb-40" data-sd3-tracks>
        <div class="container">
            <div class="sd3-tracks__head">
                <p class="sd3-label mb-15 at_fade_anim" data-fade-from="bottom">[03] Contenu</p>
                <h2 class="sd3-tracks__title reveal-text !mb-0">Ce que vous y apprenez</h2>
            </div>
        </div>

        <div class="scroll-section vertical-section !relative sd3-tracks__stack">
            <div class="wrapper">
                @foreach ($blocs as $bloc)
                    <div class="item">
                        <div class="container">
                            {{-- Même composant que la liste des filières par cycle. --}}
                            <x-site.track
                                :eyebrow="'Axe '.str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)"
                                :position="$loop->iteration"
                                :total="count($blocs)"
                                :name="$bloc['title']"
                                :description="$bloc['description']"
                                :points="$bloc['points']"
                                :image="$bloc['image']"
                                :alt="'Illustration : '.$bloc['title']"
                            />
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
