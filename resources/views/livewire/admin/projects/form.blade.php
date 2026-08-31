<div>
    <x-admin.heading :title="$pageTitle" :breadcrumb="['Administration' => route('admin.dashboard'), 'Filières' => route('admin.projects.index'), $pageTitle => null]" subtitle="Les champs laissés vides ne s’affichent pas sur le site.">
        <x-slot:actions>
            <x-admin.button variant="ghost" :href="route('admin.projects.index')">Retour à la liste</x-admin.button>
            @if ($project?->exists && $project->isPublished())
                <x-admin.button variant="secondary" :href="route('projects.show', $project)">Voir en ligne</x-admin.button>
            @endif
        </x-slot:actions>
    </x-admin.heading>

    @if (session('status'))
        <div role="status" class="mb-8 rounded-card border border-emerald-300 bg-emerald-50 px-6 py-4 text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-10 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <div class="space-y-8 rounded-card border border-ink/10 p-8">
                <x-admin.field label="Titre" for="titre" :error="$errors->first('title')">
                    <input id="titre" type="text" wire:model.blur="title"
                           class="w-full border-0 border-b border-ink/20 bg-transparent px-0 py-3 font-display text-3xl focus:border-accent focus:ring-0">
                </x-admin.field>

                <x-admin.field label="Identifiant d’URL" for="slug"
                               hint="Laissez vide pour le générer automatiquement à partir du titre."
                               :error="$errors->first('slug')">
                    <input id="slug" type="text" wire:model.blur="slug" placeholder="mon-projet"
                           class="w-full border-0 border-b border-ink/20 bg-transparent px-0 py-2 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>

                <x-admin.field label="Accroche" for="accroche"
                               hint="Une à deux phrases affichées dans les listes et le partage social."
                               :error="$errors->first('excerpt')">
                    <textarea id="accroche" rows="3" wire:model.blur="excerpt"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>

                <x-admin.field label="Description" for="description"
                               hint="Markdown accepté : **gras**, _italique_, listes, titres. Le HTML brut est retiré."
                               :error="$errors->first('description')">
                    <textarea id="description" rows="14" wire:model.blur="description"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 font-mono text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>
            </div>


            <div class="space-y-8 rounded-card border border-ink/10 p-8">
                <h2 class="font-display text-2xl">Programme</h2>
                <p class="text-xs text-ink/50">Une entrée par ligne : chaque ligne devient une puce sur la fiche publique.</p>

                <x-admin.field label="Objectifs de la formation" for="objectifs" :error="$errors->first('objectives')">
                    <textarea id="objectifs" rows="5" wire:model.blur="objectives"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>

                <x-admin.field label="Compétences clés" for="competences" :error="$errors->first('skills')">
                    <textarea id="competences" rows="5" wire:model.blur="skills"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>

                <x-admin.field label="Débouchés professionnels" for="debouches" :error="$errors->first('outcomes')">
                    <textarea id="debouches" rows="5" wire:model.blur="outcomes"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>

                <x-admin.field label="Public visé" for="public-vise" :error="$errors->first('audience')">
                    <textarea id="public-vise" rows="3" wire:model.blur="audience"
                              class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
                </x-admin.field>
            </div>

            <div class="space-y-6 rounded-card border border-ink/10 p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h2 class="font-display text-2xl">Ce que vous y apprenez</h2>
                        <p class="mt-1 text-xs text-ink/50">Les blocs affichés dans la section « Contenu » de la fiche.</p>
                    </div>
                    <x-admin.button variant="secondary" wire:click="addLearningBlock">Ajouter un bloc</x-admin.button>
                </div>

                @forelse ($learningBlocks as $index => $bloc)
                    <fieldset wire:key="bloc-{{ $index }}" class="space-y-4 rounded-card border border-ink/10 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <legend class="text-xs font-medium uppercase tracking-[0.14em] text-ink/50">
                                Bloc {{ $index + 1 }}
                            </legend>
                            <button type="button" wire:click="removeLearningBlock({{ $index }})"
                                    class="link-underline text-sm text-red-600">Retirer</button>
                        </div>

                        <x-admin.field label="Titre" :for="'bloc-titre-'.$index" :error="$errors->first('learningBlocks.'.$index.'.title')">
                            <input id="bloc-titre-{{ $index }}" type="text" wire:model.blur="learningBlocks.{{ $index }}.title"
                                   class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                        </x-admin.field>

                        <x-admin.field label="Description" :for="'bloc-desc-'.$index" :error="$errors->first('learningBlocks.'.$index.'.description')">
                            <textarea id="bloc-desc-{{ $index }}" rows="3" wire:model.blur="learningBlocks.{{ $index }}.description"
                                      class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0"></textarea>
                        </x-admin.field>

                        <x-admin.field label="Puces" :for="'bloc-points-'.$index"
                                       hint="Une par ligne."
                                       :error="$errors->first('learningBlocks.'.$index.'.points')">
                            <textarea id="bloc-points-{{ $index }}" rows="3" wire:model.blur="learningBlocks.{{ $index }}.points"
                                      class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0"></textarea>
                        </x-admin.field>

                        {{-- Visuel affiché à droite du bloc sur la fiche filière. --}}
                        <div class="space-y-3 border-t border-ink/10 pt-4">
                            @php
                                $apercuBloc = ($blockImages[$index] ?? null)?->isPreviewable()
                                    ? $blockImages[$index]->temporaryUrl()
                                    : (filled($bloc['image'] ?? null) ? Storage::url($bloc['image']) : null);
                            @endphp

                            @if ($apercuBloc)
                                <div class="flex items-start gap-4">
                                    <img src="{{ $apercuBloc }}" alt="Visuel du bloc {{ $index + 1 }}"
                                         class="aspect-[4/3] w-32 shrink-0 rounded-card object-cover">
                                    <button type="button" wire:click="removeBlockImage({{ $index }})"
                                            class="link-underline text-sm text-red-600">Retirer le visuel</button>
                                </div>
                            @endif

                            <x-admin.field label="Visuel du bloc" :for="'bloc-image-'.$index"
                                           hint="Affiché à droite du bloc. À défaut, la couverture de la filière est reprise."
                                           :error="$errors->first('blockImages.'.$index)">
                                <input id="bloc-image-{{ $index }}" type="file" accept="image/*"
                                       wire:model="blockImages.{{ $index }}"
                                       class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-5 file:py-2.5 file:text-sm file:text-paper hover:file:bg-accent">
                            </x-admin.field>

                            <div wire:loading wire:target="blockImages.{{ $index }}" class="text-sm text-ink/50">Téléversement en cours…</div>
                        </div>
                    </fieldset>
                @empty
                    <p class="text-sm text-ink/50">Aucun bloc pour le moment.</p>
                @endforelse
            </div>

            <div class="space-y-6 rounded-card border border-ink/10 p-8">
                <h2 class="font-display text-2xl">Galerie</h2>

                @if ($project?->exists && $project->images->isNotEmpty())
                    <ul class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        @foreach ($project->images as $image)
                            <li wire:key="image-{{ $image->id }}" class="group relative overflow-hidden rounded-card">
                                <img src="{{ $image->url() }}" alt="{{ $image->alt }}" class="aspect-square w-full object-cover">
                                <button type="button"
                                        wire:click="removeImage({{ $image->id }})"
                                        wire:confirm="Supprimer définitivement cette image ?"
                                        class="absolute right-2 top-2 rounded-full bg-ink/80 px-3 py-1 text-xs text-paper opacity-0 transition-opacity group-hover:opacity-100 focus:opacity-100">
                                    Retirer
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-ink/50">Aucune image dans la galerie.</p>
                @endif

                <x-admin.field label="Ajouter des images" for="galerie"
                               hint="JPG, PNG, WebP ou AVIF — 4 Mo maximum par fichier."
                               :error="$errors->first('gallery.*')">
                    <input id="galerie" type="file" multiple wire:model="gallery" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-5 file:py-2.5 file:text-sm file:text-paper hover:file:bg-accent">
                </x-admin.field>

                <div wire:loading wire:target="gallery" class="text-sm text-ink/50">Téléversement en cours…</div>
            </div>
        </div>

        <aside class="space-y-8">
            <div class="space-y-6 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Publication</h2>

                <x-admin.field label="Statut" for="statut" :error="$errors->first('status')">
                    <select id="statut" wire:model="status"
                            class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                        <option value="draft">Brouillon</option>
                        <option value="published">Publié</option>
                    </select>
                </x-admin.field>

                <x-admin.field label="Date de publication" for="date-publication"
                               hint="Une date future garde le projet masqué jusque-là."
                               :error="$errors->first('published_at')">
                    <input id="date-publication" type="datetime-local" wire:model="published_at"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>

                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" wire:model="is_featured"
                           class="mt-0.5 rounded border-ink/30 text-accent focus:ring-accent">
                    <span>
                        <span class="font-medium">Mettre en avant</span>
                        <span class="block text-xs text-ink/50">Affiché dans la sélection de la page d’accueil.</span>
                    </span>
                </label>

                <x-admin.field label="Ordre d’affichage" for="position" :error="$errors->first('position')">
                    <input id="position" type="number" min="0" wire:model="position"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>
            </div>


            @if ($cycles->isNotEmpty())
                <fieldset class="space-y-4 rounded-card border border-ink/10 p-6">
                    <legend class="font-display text-xl">Cycles</legend>
                    <p class="text-xs text-ink/50">Cochez les cycles auxquels cette filière est ouverte.</p>
                    <div class="space-y-3">
                        @foreach ($cycles as $cycle)
                            <label class="flex items-center gap-3 text-sm">
                                <input type="checkbox" value="{{ $cycle->id }}" wire:model="selectedCycles"
                                       class="rounded border-ink/30 text-accent focus:ring-accent">
                                <span>{{ $cycle->name }} <span class="text-ink/45">({{ $cycle->short_label }})</span></span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedCycles.*')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </fieldset>
            @endif

            <div class="space-y-6 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Détails</h2>

                <x-admin.field label="Catégorie" for="categorie" :error="$errors->first('category_id')">
                    <select id="categorie" wire:model="category_id"
                            class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                        <option value="">Aucune</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </x-admin.field>

                <x-admin.field label="Client" for="client" :error="$errors->first('client')">
                    <input id="client" type="text" wire:model.blur="client"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>

                <x-admin.field label="Année" for="annee" :error="$errors->first('year')">
                    <input id="annee" type="number" min="1990" max="2100" wire:model.blur="year"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>

                <x-admin.field label="Lien externe" for="lien" :error="$errors->first('external_url')">
                    <input id="lien" type="url" wire:model.blur="external_url" placeholder="https://…"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>
            </div>

            <div class="space-y-4 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Couverture</h2>

                @if ($cover?->isPreviewable())
                    <img src="{{ $cover->temporaryUrl() }}" alt="Aperçu de la nouvelle couverture" class="aspect-[4/3] w-full rounded-card object-cover">
                @elseif ($project?->coverUrl())
                    <img src="{{ $project->coverUrl() }}" alt="Couverture actuelle" class="aspect-[4/3] w-full rounded-card object-cover">
                    <button type="button" wire:click="removeCover" wire:confirm="Retirer l’image de couverture ?"
                            class="link-underline text-sm text-red-600">
                        Retirer la couverture
                    </button>
                @endif

                <x-admin.field label="Remplacer l’image" for="couverture" :error="$errors->first('cover')">
                    <input id="couverture" type="file" wire:model="cover" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-5 file:py-2.5 file:text-sm file:text-paper hover:file:bg-accent">
                </x-admin.field>

                <div wire:loading wire:target="cover" class="text-sm text-ink/50">Téléversement en cours…</div>
            </div>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer le projet</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
