<div>
    <x-admin.heading title="Filières" subtitle="Gérez les filières affichées sur le site public.">
        <x-slot:actions>
            <x-admin.button :href="route('admin.projects.create')">Nouvelle filière</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    <div class="mb-8 flex flex-wrap gap-4">
        <div class="min-w-64 flex-1">
            <label for="recherche" class="sr-only">Rechercher une filière</label>
            <input
                id="recherche"
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher une filière…"
                class="w-full rounded-full border-ink/20 bg-paper px-5 py-3 text-sm focus:border-accent focus:ring-0"
            >
        </div>

        <div>
            <label for="filtre-statut" class="sr-only">Filtrer par statut</label>
            <select
                id="filtre-statut"
                wire:model.live="status"
                class="rounded-full border-ink/20 bg-paper px-5 py-3 text-sm focus:border-accent focus:ring-0"
            >
                <option value="">Tous les statuts</option>
                <option value="published">Publiés</option>
                <option value="draft">Brouillons</option>
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-card border border-ink/10">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[52rem] text-left text-sm">
                <thead class="bg-paper-warm text-xs uppercase tracking-[0.12em] text-ink/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Filière</th>
                        <th scope="col" class="px-6 py-4 font-medium">Pôle</th>
                        <th scope="col" class="px-6 py-4 font-medium">Statut</th>
                        <th scope="col" class="px-6 py-4 font-medium">Mise en avant</th>
                        <th scope="col" class="px-6 py-4 font-medium">Modifié</th>
                        <th scope="col" class="px-6 py-4 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink/10">
                    @forelse ($projects as $project)
                        <tr wire:key="projet-{{ $project->id }}" class="hover:bg-paper-warm/50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.projects.edit', $project) }}" class="font-medium hover:text-accent">
                                    {{ $project->title }}
                                </a>
                                @if ($project->client)
                                    <p class="text-xs text-ink/45">{{ $project->client }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-ink/65">{{ $project->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4"><x-admin.status-pill :status="$project->status" /></td>
                            <td class="px-6 py-4">
                                <button
                                    type="button"
                                    wire:click="toggleFeatured({{ $project->id }})"
                                    class="text-lg leading-none transition-colors {{ $project->is_featured ? 'text-accent' : 'text-ink/25 hover:text-ink/50' }}"
                                    aria-pressed="{{ $project->is_featured ? 'true' : 'false' }}"
                                >
                                    <span class="sr-only">Basculer la mise en avant de {{ $project->title }}</span>
                                    <span aria-hidden="true">{{ $project->is_featured ? '★' : '☆' }}</span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-ink/50">{{ $project->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="link-underline text-ink/60 hover:text-ink">
                                        Modifier
                                    </a>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $project->id }})"
                                        wire:confirm="Supprimer « {{ $project->title }} » ? Le projet sera retiré du site."
                                        class="link-underline text-red-600 hover:text-red-700"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-ink/50">
                                Aucune filière ne correspond à cette recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $projects->links() }}
    </div>
</div>
