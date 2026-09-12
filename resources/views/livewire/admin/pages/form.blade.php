<div>
    <x-admin.heading :title="$header->label"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Textes du site' => route('admin.pages.index'), $header->label => null]"
                     :subtitle="$famille">
        <x-slot:actions>
            <x-admin.button variant="ghost" :href="route('admin.pages.index')">Retour à la liste</x-admin.button>
            <x-admin.button variant="secondary" :href="$header->publicUrl()" target="_blank" rel="noopener">Voir la page</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    @if (session('status'))
        <div role="status" class="mb-4 rounded-lg border border-trend-up bg-trend-up-bg px-5 py-3.5 text-sm font-medium text-trend-up">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-4 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <x-admin.card title="Titre">
                <div class="space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.field label="Libellé" for="bloc-label"
                                       hint="Le nom du bloc dans cette liste, et dans le fil d’Ariane pour une page."
                                       :error="$errors->first('label')">
                            <input id="bloc-label" type="text" wire:model.blur="label"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>

                        <x-admin.field label="Surtitre" for="bloc-eyebrow"
                                       hint="Petite mention au-dessus du titre."
                                       :error="$errors->first('eyebrow')">
                            <input id="bloc-eyebrow" type="text" wire:model.blur="eyebrow"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.field label="Titre — première ligne" for="bloc-title"
                                       :error="$errors->first('title')">
                            <input id="bloc-title" type="text" wire:model.blur="title"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-base font-semibold focus:border-brand focus:ring-0">
                        </x-admin.field>

                        <x-admin.field label="Titre — seconde ligne" for="bloc-accent"
                                       hint="Affichée en couleur d’accent. Vide : titre sur une seule ligne."
                                       :error="$errors->first('title_accent')">
                            <input id="bloc-accent" type="text" wire:model.blur="title_accent"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-base font-semibold focus:border-brand focus:ring-0">
                        </x-admin.field>
                    </div>

                    <x-admin.field label="Variantes du titre" for="bloc-variantes"
                                   hint="Une par ligne. La seconde ligne défile entre ces variantes. Vide ou une seule ligne : le titre reste immobile."
                                   :error="$errors->first('title_variants')">
                        <textarea id="bloc-variantes" rows="3" wire:model.blur="title_variants"
                                  class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                    </x-admin.field>
                </div>
            </x-admin.card>

            <x-admin.card title="Texte">
                <div class="space-y-5">
                    <x-admin.field label="Accroche" for="bloc-intro"
                                   hint="Paragraphe court sous le titre."
                                   :error="$errors->first('intro')">
                        <textarea id="bloc-intro" rows="3" wire:model.blur="intro"
                                  class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                    </x-admin.field>

                    <x-admin.field label="Texte long" for="bloc-body"
                                   hint="Utilisé par les sections qui en prévoient un, comme le mot du directeur. Laissez une ligne vide entre deux paragraphes."
                                   :error="$errors->first('body')">
                        <textarea id="bloc-body" rows="8" wire:model.blur="body"
                                  class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm leading-relaxed focus:border-brand focus:ring-0"></textarea>
                    </x-admin.field>

                    <x-admin.field label="Étiquettes" for="bloc-tags"
                                   hint="Une par ligne. Quand une entrée porte deux informations, séparez-les par une barre verticale : « 4 | Cycles de formation »."
                                   :error="$errors->first('tags')">
                        <textarea id="bloc-tags" rows="4" wire:model.blur="tags"
                                  class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                    </x-admin.field>
                </div>
            </x-admin.card>
        </div>

        <aside class="space-y-4">
            <x-admin.card title="Bouton">
                <div class="space-y-5">
                    <x-admin.field label="Libellé" for="bloc-cta"
                                   hint="Vide : pas de bouton."
                                   :error="$errors->first('cta_label')">
                        <input id="bloc-cta" type="text" wire:model.blur="cta_label"
                               class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                    </x-admin.field>

                    <x-admin.field label="Lien" for="bloc-cta-url"
                                   hint="Par exemple /contact."
                                   :error="$errors->first('cta_url')">
                        <input id="bloc-cta-url" type="text" wire:model.blur="cta_url" placeholder="/contact"
                               class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <x-admin.card title="Visuel de fond">
                <div class="space-y-3">
                    @php
                        $apercu = $image?->isPreviewable()
                            ? $image->temporaryUrl()
                            : ($imageRetiree ? null : $header->imageUrl());
                    @endphp

                    @if ($apercu)
                        <img src="{{ $apercu }}" alt="Aperçu du visuel"
                             class="aspect-[16/10] w-full rounded-md border border-app-line object-cover">
                        <button type="button" wire:click="removeImage" wire:confirm="Retirer ce visuel ?"
                                class="text-[13px] font-medium text-trend-down hover:underline">
                            Retirer le visuel
                        </button>
                    @else
                        <div class="grid aspect-[16/10] w-full place-items-center rounded-md border border-dashed border-app-line bg-app-bg px-4 text-center text-[13px] text-app-muted">
                            Sans visuel, le bandeau reste sur fond sombre.
                        </div>
                    @endif

                    <x-admin.field label="Remplacer" for="bloc-image"
                                   hint="JPG, PNG, WebP ou AVIF — 4 Mo maximum."
                                   :error="$errors->first('image')">
                        <input id="bloc-image" type="file" accept="image/*" wire:model="image"
                               class="block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:text-white hover:file:bg-brand-dark">
                    </x-admin.field>

                    <div wire:loading wire:target="image" class="text-[13px] text-app-muted">Téléversement en cours…</div>
                </div>
            </x-admin.card>

            <x-admin.card title="Pictogramme">
                <x-admin.field label="Icône" for="bloc-icon"
                               hint="Affiché sur les cartes qui en prévoient un. Sans objet ailleurs."
                               :error="$errors->first('icon')">
                    <select id="bloc-icon" wire:model.blur="icon"
                            class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        <option value="">Aucun</option>
                        @foreach ($icones as $nom)
                            <option value="{{ $nom }}">{{ $nom }}</option>
                        @endforeach
                    </select>
                </x-admin.field>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer le bloc</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
