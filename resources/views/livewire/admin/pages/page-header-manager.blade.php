<div>
    <x-admin.heading title="Textes du site"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Textes du site' => null]"
                     subtitle="Les bandeaux des pages internes et les sections de la page d’accueil." />

    @if (session('status'))
        <div role="status" class="mb-4 rounded-lg border border-trend-up bg-trend-up-bg px-5 py-3.5 text-sm font-medium text-trend-up">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        @foreach ($headers as $index => $header)
            @if ($loop->first || $header['group'] !== $headers[$index - 1]['group'])
                <p class="pt-4 text-xs font-semibold uppercase tracking-[0.08em] text-app-muted">
                    {{ $header['group'] === 'accueil' ? 'Sections de la page d’accueil' : 'Bandeaux des pages internes' }}
                </p>
            @endif

            <x-admin.card wire:key="entete-{{ $header['id'] }}">
                <x-slot:title>{{ $header['label'] }}</x-slot:title>
                <x-slot:actions>
                    <a href="{{ url(match (true) {
                            str_starts_with($header['key'], 'home.') => '/',
                            $header['key'] === 'about' => '/l-ecole',
                            $header['key'] === 'filieres' => '/filieres',
                            $header['key'] === 'journal' => '/journal',
                            $header['key'] === 'equipe' => '/equipe',
                            $header['key'] === 'recherche' => '/recherche',
                            default => '/contact',
                        }) }}"
                       target="_blank" rel="noopener" class="text-[13px] font-medium text-brand hover:underline">
                        Voir la page
                    </a>
                </x-slot:actions>

                <div class="grid gap-5 lg:grid-cols-3">
                    {{-- Colonne texte --}}
                    <div class="space-y-5 lg:col-span-2">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-admin.field label="Libellé du fil d’Ariane" :for="'entete-label-'.$index"
                                           hint="Le nom de la page dans « Accueil / … »."
                                           :error="$errors->first('headers.'.$index.'.label')">
                                <input id="entete-label-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.label"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                            </x-admin.field>

                            <x-admin.field label="Surtitre" :for="'entete-eyebrow-'.$index"
                                           hint="Petite mention au-dessus du titre."
                                           :error="$errors->first('headers.'.$index.'.eyebrow')">
                                <input id="entete-eyebrow-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.eyebrow"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                            </x-admin.field>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-admin.field label="Titre — première ligne" :for="'entete-title-'.$index"
                                           :error="$errors->first('headers.'.$index.'.title')">
                                <input id="entete-title-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.title"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-base font-semibold focus:border-brand focus:ring-0">
                            </x-admin.field>

                            <x-admin.field label="Titre — seconde ligne" :for="'entete-accent-'.$index"
                                           hint="Affichée en couleur d’accent. Laissez vide pour un titre sur une seule ligne."
                                           :error="$errors->first('headers.'.$index.'.title_accent')">
                                <input id="entete-accent-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.title_accent"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-base font-semibold focus:border-brand focus:ring-0">
                            </x-admin.field>
                        </div>

                        <x-admin.field label="Accroche" :for="'entete-intro-'.$index"
                                       hint="Paragraphe court sous le titre. Facultatif."
                                       :error="$errors->first('headers.'.$index.'.intro')">
                            <textarea id="entete-intro-{{ $index }}" rows="2" wire:model.blur="headers.{{ $index }}.intro"
                                      class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                        </x-admin.field>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-admin.field label="Libellé du bouton" :for="'entete-cta-'.$index"
                                           hint="Laissez vide pour masquer le bouton."
                                           :error="$errors->first('headers.'.$index.'.cta_label')">
                                <input id="entete-cta-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.cta_label"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                            </x-admin.field>

                            <x-admin.field label="Lien du bouton" :for="'entete-cta-url-'.$index"
                                           hint="Par exemple /contact. Par défaut, la page Contact."
                                           :error="$errors->first('headers.'.$index.'.cta_url')">
                                <input id="entete-cta-url-{{ $index }}" type="text" wire:model.blur="headers.{{ $index }}.cta_url"
                                       placeholder="/contact"
                                       class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                            </x-admin.field>
                        </div>

                        <x-admin.field label="Texte long" :for="'entete-body-'.$index"
                                       hint="Utilisé par les sections qui en prévoient un, comme le mot du directeur. Laissez une ligne vide entre deux paragraphes."
                                       :error="$errors->first('headers.'.$index.'.body')">
                            <textarea id="entete-body-{{ $index }}" rows="6" wire:model.blur="headers.{{ $index }}.body"
                                      class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm leading-relaxed focus:border-brand focus:ring-0"></textarea>
                        </x-admin.field>

                        <x-admin.field label="Pictogramme" :for="'entete-icon-'.$index"
                                       hint="Affiché sur les cartes qui en prévoient un. Sans objet ailleurs."
                                       :error="$errors->first('headers.'.$index.'.icon')">
                            <select id="entete-icon-{{ $index }}" wire:model.blur="headers.{{ $index }}.icon"
                                    class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                                <option value="">Aucun</option>
                                @foreach ($icones as $icone)
                                    <option value="{{ $icone }}">{{ $icone }}</option>
                                @endforeach
                            </select>
                        </x-admin.field>

                        <x-admin.field label="Étiquettes" :for="'entete-tags-'.$index"
                                       hint="Une par ligne. Quand une entrée porte deux informations, séparez-les par une barre verticale : « 4 | Cycles de formation »."
                                       :error="$errors->first('headers.'.$index.'.tags')">
                            <textarea id="entete-tags-{{ $index }}" rows="3" wire:model.blur="headers.{{ $index }}.tags"
                                      class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                        </x-admin.field>
                    </div>

                    {{-- Colonne visuel --}}
                    <div class="space-y-3">
                        @php
                            $apercu = ($images[$index] ?? null)?->isPreviewable()
                                ? $images[$index]->temporaryUrl()
                                : $header['image_url'];
                        @endphp

                        <p class="text-xs font-semibold uppercase tracking-[0.06em] text-app-muted">Visuel de fond</p>

                        @if ($apercu)
                            <img src="{{ $apercu }}" alt="Aperçu du visuel de {{ $header['label'] }}"
                                 class="aspect-[16/10] w-full rounded-md border border-app-line object-cover">
                            <button type="button" wire:click="removeImage({{ $index }})"
                                    wire:confirm="Retirer ce visuel ?"
                                    class="text-[13px] font-medium text-trend-down hover:underline">
                                Retirer le visuel
                            </button>
                        @else
                            <div class="grid aspect-[16/10] w-full place-items-center rounded-md border border-dashed border-app-line bg-app-bg text-center text-[13px] text-app-muted">
                                Sans visuel,<br>le bandeau reste sur fond sombre.
                            </div>
                        @endif

                        <x-admin.field label="Remplacer" :for="'entete-image-'.$index"
                                       hint="JPG, PNG, WebP ou AVIF — 4 Mo maximum."
                                       :error="$errors->first('images.'.$index)">
                            <input id="entete-image-{{ $index }}" type="file" accept="image/*"
                                   wire:model="images.{{ $index }}"
                                   class="block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:text-white hover:file:bg-brand-dark">
                        </x-admin.field>

                        <div wire:loading wire:target="images.{{ $index }}" class="text-[13px] text-app-muted">Téléversement en cours…</div>
                    </div>
                </div>
            </x-admin.card>
        @endforeach

        <x-admin.button type="submit" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Enregistrer les en-têtes</span>
            <span wire:loading wire:target="save">Enregistrement…</span>
        </x-admin.button>
    </form>
</div>
