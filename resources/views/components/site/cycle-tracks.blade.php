@props([
    'cycles',
    'eyebrow' => null,
    'title' => null,
])

{{--
    Liste les filières regroupées par cycle, dans la mise en page « tracks » de
    services-details-3. Chaque cycle est une section ; chaque filière un
    <x-site.track>. Les cycles sans filière rattachée sont ignorés.
--}}
@php
    $cyclesRemplis = $cycles->filter(fn ($cycle) => $cycle->projects->isNotEmpty());
@endphp

@if ($cyclesRemplis->isNotEmpty())
    <section class="sd3-tracks pt-20 pb-40" aria-labelledby="titre-filieres-par-cycle">
        <div class="container">
            <div class="sd3-tracks__head">
                @if ($eyebrow)
                    <p class="sd3-label mb-15 at_fade_anim" data-fade-from="bottom">{{ $eyebrow }}</p>
                @endif
                <h2 id="titre-filieres-par-cycle" class="sd3-tracks__title reveal-text !mb-0">
                    {{ $title ?? 'Nos filières par cycle' }}
                </h2>
            </div>
        </div>

        @foreach ($cyclesRemplis as $cycle)
            <div class="container pt-60">
                <div class="sd3-tracks__head">
                    <p class="sd3-label mb-15">{{ $cycle->short_label ?? $cycle->name }}</p>
                    <h3 class="sd3-tracks__title !mb-0">{{ $cycle->name }}</h3>
                    @if ($cycle->description)
                        <p class="pt-15">{{ $cycle->description }}</p>
                    @endif
                </div>
            </div>

            @foreach ($cycle->projects as $filiere)
                <div class="container pt-30">
                    <x-site.track
                        :eyebrow="$cycle->short_label ?? $cycle->name"
                        :position="$loop->iteration"
                        :total="$cycle->projects->count()"
                        :name="$filiere->title"
                        :description="$filiere->excerpt"
                        :points="$filiere->category ? [$filiere->category->name] : []"
                        :image="$filiere->coverUrl()"
                        :alt="'Filière '.$filiere->title"
                        :href="route('projects.show', $filiere)"
                    />
                </div>
            @endforeach
        @endforeach
    </section>
@endif
