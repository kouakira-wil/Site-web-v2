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

    {{--
        Les champs viennent de PageHeaderSchema : ce bloc n'affiche que ceux
        que sa section lit réellement, avec les intitulés de ce bloc-là.
    --}}
    <form wire:submit="save" class="grid gap-4 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <x-admin.card title="Contenu">
                <div class="space-y-5">
                    @foreach ($champs as $champ)
                        @continue($champ['type'] === 'fichier')

                        @php $id = 'bloc-'.$champ['name']; @endphp

                        <x-admin.field :label="$champ['label']" :for="$id"
                                       :hint="$champ['hint']"
                                       :error="$errors->first($champ['name'])">
                            @switch($champ['type'])
                                @case('zone')
                                    <textarea id="{{ $id }}" rows="3" wire:model.blur="{{ $champ['name'] }}"
                                              class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0"></textarea>
                                    @break

                                @case('zone-longue')
                                    <textarea id="{{ $id }}" rows="10" wire:model.blur="{{ $champ['name'] }}"
                                              class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm leading-relaxed focus:border-brand focus:ring-0"></textarea>
                                    @break

                                @case('lignes')
                                    <textarea id="{{ $id }}" rows="4" wire:model.blur="{{ $champ['name'] }}"
                                              class="w-full rounded-md border-app-line bg-white px-3 py-2 font-mono text-sm focus:border-brand focus:ring-0"></textarea>
                                    @break

                                @case('icone')
                                    <select id="{{ $id }}" wire:model.blur="icon"
                                            class="w-full rounded-md border-app-line bg-white px-3 py-2 text-sm focus:border-brand focus:ring-0">
                                        <option value="">Aucun</option>
                                        @foreach ($icones as $nom)
                                            <option value="{{ $nom }}">{{ $nom }}</option>
                                        @endforeach
                                    </select>
                                    @break

                                @default
                                    <input id="{{ $id }}" type="text" wire:model.blur="{{ $champ['name'] }}"
                                           @class([
                                               'w-full rounded-md border-app-line bg-white px-3 py-2 focus:border-brand focus:ring-0',
                                               'text-base font-semibold' => in_array($champ['name'], ['title', 'title_accent'], true),
                                               'text-sm' => ! in_array($champ['name'], ['title', 'title_accent'], true),
                                           ])>
                            @endswitch
                        </x-admin.field>
                    @endforeach
                </div>
            </x-admin.card>
        </div>

        <aside class="space-y-4">
            {{-- Une carte par champ fichier du schéma : certains blocs en ont deux. --}}
            @foreach (collect($champs)->where('type', 'fichier') as $champFichier)
                @php
                    $nom = $champFichier['name'];
                    $televerse = $this->{$nom} ?? null;
                    $stocke = $nom === 'image' ? $header->imageUrl() : $header->secondaryImageUrl();
                    $apercu = $televerse?->isPreviewable()
                        ? $televerse->temporaryUrl()
                        : (($retraits[$nom] ?? false) ? null : $stocke);
                @endphp

                <x-admin.card :key="'fichier-'.$nom" :title="$champFichier['label']">
                    <div class="space-y-3">
                        @if ($apercu)
                            <img src="{{ $apercu }}" alt="Aperçu : {{ $champFichier['label'] }}"
                                 class="aspect-[16/10] w-full rounded-md border border-app-line object-cover">
                            <button type="button" wire:click="removeImage('{{ $nom }}')"
                                    wire:confirm="Retirer cette image ?"
                                    class="text-[13px] font-medium text-trend-down hover:underline">
                                Retirer
                            </button>
                        @else
                            <div class="grid aspect-[16/10] w-full place-items-center rounded-md border border-dashed border-app-line bg-app-bg px-4 text-center text-[13px] text-app-muted">
                                {{ $champFichier['hint'] ?? 'Aucune image pour le moment.' }}
                            </div>
                        @endif

                        <x-admin.field label="Téléverser" :for="'bloc-'.$nom"
                                       hint="JPG, PNG, WebP ou AVIF — 4 Mo maximum."
                                       :error="$errors->first($nom)">
                            <input id="bloc-{{ $nom }}" type="file" accept="image/*" wire:model="{{ $nom }}"
                                   class="block w-full text-sm file:mr-3 file:rounded-md file:border-0 file:bg-brand file:px-4 file:py-2 file:text-sm file:text-white hover:file:bg-brand-dark">
                        </x-admin.field>

                        <div wire:loading wire:target="{{ $nom }}" class="text-[13px] text-app-muted">Téléversement en cours…</div>
                    </div>
                </x-admin.card>
            @endforeach

            <x-admin.card title="Où s’affiche ce bloc">
                <p class="text-[13px] leading-relaxed text-app-muted">
                    {{ $famille }} — <a href="{{ $header->publicUrl() }}" target="_blank" rel="noopener" class="font-medium text-brand hover:underline">voir la page</a>.
                </p>
                <p class="mt-3 text-[13px] leading-relaxed text-app-muted">
                    Seuls les champs utilisés par cette section sont proposés ici.
                </p>
            </x-admin.card>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer le bloc</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
