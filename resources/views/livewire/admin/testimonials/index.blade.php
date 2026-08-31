<div>
    <x-admin.heading title="Témoignages"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Témoignages' => null]"
                     subtitle="Les paroles de diplômés affichées sur la page d’accueil.">
        <x-slot:actions>
            <x-admin.button :href="route('admin.testimonials.create')">Nouveau témoignage</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    <x-admin.card body-class="p-0">
        @forelse ($testimonials as $testimonial)
            <article wire:key="temoignage-{{ $testimonial->id }}"
                     class="flex flex-wrap items-start gap-4 border-b border-app-line px-5 py-4 last:border-b-0">
                <span class="grid h-12 w-12 shrink-0 place-items-center overflow-hidden rounded-full bg-tile-violet text-sm font-semibold text-tile-violet-ink">
                    @if ($testimonial->photoUrl())
                        <img src="{{ $testimonial->photoUrl() }}" alt="" class="h-full w-full object-cover">
                    @else
                        {{ $testimonial->initial() }}
                    @endif
                </span>

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold">{{ $testimonial->signature() }}</p>
                    <p class="text-[13px] text-app-muted">
                        {{ $testimonial->program ?: 'Formation non précisée' }}
                        @if ($testimonial->graduation_year) · promotion {{ $testimonial->graduation_year }} @endif
                    </p>
                    <p class="mt-2 text-[13px] leading-relaxed text-app-text/80">
                        {{ \Illuminate\Support\Str::limit($testimonial->quote, 160) }}
                    </p>
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    <button type="button" wire:click="toggleActive({{ $testimonial->id }})"
                            @class([
                                'rounded px-2 py-1 text-xs font-semibold',
                                'bg-trend-up-bg text-trend-up' => $testimonial->is_active,
                                'bg-app-bg text-app-muted' => ! $testimonial->is_active,
                            ])>
                        {{ $testimonial->is_active ? 'En ligne' : 'Masqué' }}
                    </button>

                    <x-admin.button variant="secondary" :href="route('admin.testimonials.edit', $testimonial)">Modifier</x-admin.button>

                    <x-admin.button variant="danger" wire:click="delete({{ $testimonial->id }})"
                                    wire:confirm="Supprimer définitivement ce témoignage ?">
                        Supprimer
                    </x-admin.button>
                </div>
            </article>
        @empty
            <p class="px-5 py-12 text-center text-[13px] text-app-muted">Aucun témoignage pour le moment.</p>
        @endforelse
    </x-admin.card>
</div>
