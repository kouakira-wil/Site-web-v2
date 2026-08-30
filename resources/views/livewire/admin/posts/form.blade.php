<div>
    <x-admin.heading :title="$pageTitle" subtitle="Le contenu accepte le Markdown ; le HTML brut est retiré au rendu.">
        <x-slot:actions>
            <x-admin.button variant="ghost" :href="route('admin.posts.index')">Retour à la liste</x-admin.button>
            @if ($post?->exists && $post->isPublished())
                <x-admin.button variant="secondary" :href="route('posts.show', $post)">Voir en ligne</x-admin.button>
            @endif
        </x-slot:actions>
    </x-admin.heading>

    @if (session('status'))
        <div role="status" class="mb-8 rounded-card border border-emerald-300 bg-emerald-50 px-6 py-4 text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-10 lg:grid-cols-3">
        <div class="space-y-8 rounded-card border border-ink/10 p-8 lg:col-span-2">
            <x-admin.field label="Titre" for="titre-article" :error="$errors->first('title')">
                <input id="titre-article" type="text" wire:model.blur="title"
                       class="w-full border-0 border-b border-ink/20 bg-transparent px-0 py-3 font-display text-3xl focus:border-accent focus:ring-0">
            </x-admin.field>

            <x-admin.field label="Identifiant d’URL" for="slug-article"
                           hint="Laissez vide pour le générer depuis le titre."
                           :error="$errors->first('slug')">
                <input id="slug-article" type="text" wire:model.blur="slug" placeholder="mon-article"
                       class="w-full border-0 border-b border-ink/20 bg-transparent px-0 py-2 text-sm focus:border-accent focus:ring-0">
            </x-admin.field>

            <x-admin.field label="Accroche" for="accroche-article"
                           hint="Reprise dans les listes, le partage social et en tête d’article."
                           :error="$errors->first('excerpt')">
                <textarea id="accroche-article" rows="3" wire:model.blur="excerpt"
                          class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0"></textarea>
            </x-admin.field>

            <x-admin.field label="Contenu" for="contenu-article"
                           hint="Markdown : # Titre, **gras**, _italique_, - listes, [lien](url)."
                           :error="$errors->first('body')">
                <textarea id="contenu-article" rows="22" wire:model.blur="body"
                          class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 font-mono text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
            </x-admin.field>
        </div>

        <aside class="space-y-8">
            <div class="space-y-6 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Publication</h2>

                <x-admin.field label="Statut" for="statut-article" :error="$errors->first('status')">
                    <select id="statut-article" wire:model="status"
                            class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                        <option value="draft">Brouillon</option>
                        <option value="published">Publié</option>
                    </select>
                </x-admin.field>

                <x-admin.field label="Date de publication" for="date-article"
                               hint="Une date future programme la parution."
                               :error="$errors->first('published_at')">
                    <input id="date-article" type="datetime-local" wire:model="published_at"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>

                <x-admin.field label="Catégorie" for="categorie-article" :error="$errors->first('category_id')">
                    <select id="categorie-article" wire:model="category_id"
                            class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                        <option value="">Aucune</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
            </div>

            @if ($tags->isNotEmpty())
                <fieldset class="space-y-4 rounded-card border border-ink/10 p-6">
                    <legend class="font-display text-xl">Étiquettes</legend>
                    <div class="space-y-3">
                        @foreach ($tags as $tag)
                            <label class="flex items-center gap-3 text-sm">
                                <input type="checkbox" value="{{ $tag->id }}" wire:model="selectedTags"
                                       class="rounded border-ink/30 text-accent focus:ring-accent">
                                <span>{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endif

            <div class="space-y-4 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Couverture</h2>

                @if ($cover?->isPreviewable())
                    <img src="{{ $cover->temporaryUrl() }}" alt="Aperçu de la nouvelle couverture" class="aspect-[16/10] w-full rounded-card object-cover">
                @elseif ($post?->coverUrl())
                    <img src="{{ $post->coverUrl() }}" alt="Couverture actuelle" class="aspect-[16/10] w-full rounded-card object-cover">
                    <button type="button" wire:click="removeCover" wire:confirm="Retirer l’image de couverture ?"
                            class="link-underline text-sm text-red-600">
                        Retirer la couverture
                    </button>
                @endif

                <x-admin.field label="Remplacer l’image" for="couverture-article" :error="$errors->first('cover')">
                    <input id="couverture-article" type="file" wire:model="cover" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-5 file:py-2.5 file:text-sm file:text-paper hover:file:bg-accent">
                </x-admin.field>

                <div wire:loading wire:target="cover" class="text-sm text-ink/50">Téléversement en cours…</div>
            </div>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer l’article</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
