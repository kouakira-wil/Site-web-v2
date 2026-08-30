<div>
    <x-admin.heading title="Équipe" subtitle="Les membres masqués restent enregistrés mais disparaissent du site.">
        <x-slot:actions>
            <x-admin.button :href="route('admin.team.create')">Ajouter un membre</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    @if ($members->isEmpty())
        <div class="rounded-card border border-dashed border-ink/20 px-6 py-20 text-center">
            <p class="text-ink/55">Aucun membre enregistré pour le moment.</p>
            <x-admin.button class="mt-6" :href="route('admin.team.create')">Ajouter le premier membre</x-admin.button>
        </div>
    @else
        <ul class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($members as $member)
                <li wire:key="membre-{{ $member->id }}" class="flex gap-5 rounded-card border border-ink/10 p-5">
                    @if ($member->photoUrl())
                        <img src="{{ $member->photoUrl() }}" alt="" class="h-20 w-20 shrink-0 rounded-card object-cover">
                    @else
                        <span aria-hidden="true" class="grid h-20 w-20 shrink-0 place-items-center rounded-card bg-paper-warm font-display text-2xl text-ink/40">
                            {{ $member->initials() }}
                        </span>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ $member->name }}</p>
                        <p class="truncate text-sm text-ink/55">{{ $member->role }}</p>

                        <p class="mt-2">
                            <span @class([
                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-emerald-100 text-emerald-800' => $member->is_active,
                                'bg-ink/10 text-ink/60' => ! $member->is_active,
                            ])>
                                {{ $member->is_active ? 'Visible' : 'Masqué' }}
                            </span>
                        </p>

                        <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm">
                            <a href="{{ route('admin.team.edit', $member) }}" class="link-underline text-ink/60 hover:text-ink">
                                Modifier
                            </a>
                            <button type="button" wire:click="toggleActive({{ $member->id }})"
                                    class="link-underline text-ink/60 hover:text-ink">
                                {{ $member->is_active ? 'Masquer' : 'Afficher' }}
                            </button>
                            <button type="button" wire:click="delete({{ $member->id }})"
                                    wire:confirm="Supprimer {{ $member->name }} de l’équipe ?"
                                    class="link-underline text-red-600 hover:text-red-700">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
