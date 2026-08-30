<div>
    <x-admin.heading title="Actualités" subtitle="Rédigez et publiez les actualités de l’école.">
        <x-slot:actions>
            <x-admin.button :href="route('admin.posts.create')">Nouvelle actualité</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    <div class="mb-8 flex flex-wrap gap-4">
        <div class="min-w-64 flex-1">
            <label for="recherche-article" class="sr-only">Rechercher une actualité</label>
            <input id="recherche-article" type="search" wire:model.live.debounce.300ms="search"
                   placeholder="Rechercher par titre…"
                   class="w-full rounded-full border-ink/20 bg-paper px-5 py-3 text-sm focus:border-accent focus:ring-0">
        </div>

        <div>
            <label for="filtre-statut-article" class="sr-only">Filtrer par statut</label>
            <select id="filtre-statut-article" wire:model.live="status"
                    class="rounded-full border-ink/20 bg-paper px-5 py-3 text-sm focus:border-accent focus:ring-0">
                <option value="">Tous les statuts</option>
                <option value="published">Publiés</option>
                <option value="draft">Brouillons</option>
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-card border border-ink/10">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[48rem] text-left text-sm">
                <thead class="bg-paper-warm text-xs uppercase tracking-[0.12em] text-ink/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium">Actualité</th>
                        <th scope="col" class="px-6 py-4 font-medium">Catégorie</th>
                        <th scope="col" class="px-6 py-4 font-medium">Statut</th>
                        <th scope="col" class="px-6 py-4 font-medium">Publication</th>
                        <th scope="col" class="px-6 py-4 text-right font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-ink/10">
                    @forelse ($posts as $post)
                        <tr wire:key="article-{{ $post->id }}" class="hover:bg-paper-warm/50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="font-medium hover:text-accent">
                                    {{ $post->title }}
                                </a>
                                <p class="text-xs text-ink/45">
                                    {{ $post->author?->name ?? 'Auteur inconnu' }} · {{ $post->reading_minutes }} min
                                </p>
                            </td>
                            <td class="px-6 py-4 text-ink/65">{{ $post->category?->name ?? '—' }}</td>
                            <td class="px-6 py-4"><x-admin.status-pill :status="$post->status" /></td>
                            <td class="px-6 py-4 text-ink/50">
                                {{ $post->published_at?->translatedFormat('d M Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="link-underline text-ink/60 hover:text-ink">
                                        Modifier
                                    </a>
                                    <button type="button" wire:click="delete({{ $post->id }})"
                                            wire:confirm="Supprimer « {{ $post->title }} » ?"
                                            class="link-underline text-red-600 hover:text-red-700">
                                        Supprimer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-ink/50">
                                Aucune actualité ne correspond à cette recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</div>
