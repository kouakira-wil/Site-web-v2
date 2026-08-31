<div>
    <x-admin.heading :title="$pageTitle"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Témoignages' => route('admin.testimonials.index'), $pageTitle => null]"
                     subtitle="Laissez le nom vide pour un témoignage anonyme : la signature reprendra alors la promotion.">
        <x-slot:actions>
            <x-admin.button variant="ghost" :href="route('admin.testimonials.index')">Retour à la liste</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    @if (session('status'))
        <div role="status" class="mb-4 rounded-lg border border-trend-up bg-trend-up-bg px-5 py-3.5 text-sm font-medium text-trend-up">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-admin.card title="Le témoignage">
                <div class="space-y-5">
                    <x-admin.field label="Texte du témoignage" for="temoignage-texte"
                                   hint="Les guillemets « » sont ajoutés à la saisie, comme sur les témoignages existants."
                                   :error="$errors->first('quote')">
                        <textarea id="temoignage-texte" rows="5" wire:model.blur="quote"
                                  class="w-full rounded-md border-app-line bg-white px-3 py-2.5 text-sm leading-relaxed focus:border-brand focus:ring-0"></textarea>
                    </x-admin.field>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.field label="Nom" for="temoignage-auteur"
                                       hint="Facultatif. Vide = signature par la promotion."
                                       :error="$errors->first('author')">
                            <input id="temoignage-auteur" type="text" wire:model.blur="author"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>

                        <x-admin.field label="Formation suivie" for="temoignage-formation"
                                       :error="$errors->first('program')">
                            <input id="temoignage-formation" type="text" wire:model.blur="program"
                                   placeholder="Comptabilité, Banque et Finance"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.field label="Année d’obtention du diplôme" for="temoignage-annee"
                                       :error="$errors->first('graduation_year')">
                            <input id="temoignage-annee" type="number" min="1990" max="{{ now()->year + 1 }}"
                                   wire:model.blur="graduation_year" placeholder="{{ now()->year }}"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>

                        <x-admin.field label="Ville" for="temoignage-ville"
                                       :error="$errors->first('location')">
                            <input id="temoignage-ville" type="text" wire:model.blur="location"
                                   placeholder="Brazzaville, Congo"
                                   class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                        </x-admin.field>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <aside class="space-y-4">
            <x-admin.card title="Photo">
                <div class="space-y-3">
                    @php
                        $apercu = $photo?->isPreviewable() ? $photo->temporaryUrl() : $testimonial?->photoUrl();
                    @endphp

                    @if ($apercu)
                        <img src="{{ $apercu }}" alt="Aperçu de la photo"
                             class="aspect-square w-32 rounded-full border border-app-line object-cover">
                        <button type="button" wire:click="removePhoto" wire:confirm="Retirer cette photo ?"
                                class="block text-[13px] font-medium text-trend-down hover:underline">
                            Retirer la photo
                        </button>
                    @else
                        <p class="text-[13px] text-app-muted">Sans photo, l’initiale s’affiche dans une pastille.</p>
                    @endif

                    <x-admin.field label="Téléverser" for="temoignage-photo"
                                   hint="JPG, PNG, WebP ou AVIF — 4 Mo maximum."
                                   :error="$errors->first('photo')">
                        <input id="temoignage-photo" type="file" accept="image/*" wire:model="photo"
                               class="block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:text-white hover:file:bg-brand-dark">
                    </x-admin.field>

                    <div wire:loading wire:target="photo" class="text-[13px] text-app-muted">Téléversement en cours…</div>
                </div>
            </x-admin.card>

            <x-admin.card title="Affichage">
                <div class="space-y-5">
                    <label class="flex items-center gap-3 text-sm">
                        <input type="checkbox" wire:model="is_active"
                               class="rounded border-app-line text-brand focus:ring-brand">
                        <span>Afficher sur le site</span>
                    </label>

                    <x-admin.field label="Ordre" for="temoignage-position"
                                   hint="Le plus petit nombre passe en premier."
                                   :error="$errors->first('position')">
                        <input id="temoignage-position" type="number" min="0" wire:model.blur="position"
                               class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                    </x-admin.field>
                </div>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer le témoignage</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
