<div>
    <x-admin.heading :title="$pageTitle" :breadcrumb="['Administration' => route('admin.dashboard'), 'Équipe' => route('admin.team.index'), $pageTitle => null]" subtitle="Ces informations alimentent la page Équipe du site public.">
        <x-slot:actions>
            <x-admin.button variant="ghost" :href="route('admin.team.index')">Retour à la liste</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    @if (session('status'))
        <div role="status" class="mb-8 rounded-card border border-emerald-300 bg-emerald-50 px-6 py-4 text-emerald-900">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="save" class="grid gap-10 lg:grid-cols-3">
        <div class="space-y-8 rounded-card border border-ink/10 p-8 lg:col-span-2">
            <x-admin.field label="Nom" for="nom-membre" :error="$errors->first('name')">
                <input id="nom-membre" type="text" wire:model.blur="name"
                       class="w-full border-0 border-b border-ink/20 bg-transparent px-0 py-3 font-display text-3xl focus:border-accent focus:ring-0">
            </x-admin.field>

            <x-admin.field label="Fonction" for="fonction-membre" :error="$errors->first('role')">
                <input id="fonction-membre" type="text" wire:model.blur="role" placeholder="Directrice artistique"
                       class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0">
            </x-admin.field>

            <x-admin.field label="Biographie" for="bio-membre"
                           hint="Deux à quatre phrases suffisent."
                           :error="$errors->first('bio')">
                <textarea id="bio-membre" rows="6" wire:model.blur="bio"
                          class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm leading-relaxed focus:border-accent focus:ring-0"></textarea>
            </x-admin.field>

            <x-admin.field label="Adresse e-mail" for="email-membre"
                           hint="Affichée publiquement si renseignée."
                           :error="$errors->first('email')">
                <input id="email-membre" type="email" wire:model.blur="email"
                       class="w-full rounded-card border-ink/20 bg-transparent px-4 py-3 text-sm focus:border-accent focus:ring-0">
            </x-admin.field>

            <fieldset class="space-y-5">
                <legend class="text-xs font-medium uppercase tracking-[0.14em] text-ink/50">Réseaux</legend>

                @foreach ($networks as $key => $label)
                    <x-admin.field :label="$label" :for="'reseau-'.$key" :error="$errors->first('socials.'.$key)">
                        <input id="reseau-{{ $key }}" type="url" wire:model.blur="socials.{{ $key }}" placeholder="https://…"
                               class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                    </x-admin.field>
                @endforeach
            </fieldset>
        </div>

        <aside class="space-y-8">
            <div class="space-y-6 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Affichage</h2>

                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" wire:model="is_active"
                           class="mt-0.5 rounded border-ink/30 text-accent focus:ring-accent">
                    <span>
                        <span class="font-medium">Visible sur le site</span>
                        <span class="block text-xs text-ink/50">Décochez pour retirer sans supprimer.</span>
                    </span>
                </label>

                <x-admin.field label="Ordre d’affichage" for="position-membre"
                               hint="Le plus petit nombre apparaît en premier."
                               :error="$errors->first('position')">
                    <input id="position-membre" type="number" min="0" wire:model="position"
                           class="w-full rounded-card border-ink/20 bg-transparent px-4 py-2.5 text-sm focus:border-accent focus:ring-0">
                </x-admin.field>
            </div>

            <div class="space-y-4 rounded-card border border-ink/10 p-6">
                <h2 class="font-display text-xl">Portrait</h2>

                @if ($photo?->isPreviewable())
                    <img src="{{ $photo->temporaryUrl() }}" alt="Aperçu du nouveau portrait" class="aspect-[3/4] w-full rounded-card object-cover">
                @elseif ($member?->photoUrl())
                    <img src="{{ $member->photoUrl() }}" alt="Portrait actuel" class="aspect-[3/4] w-full rounded-card object-cover">
                    <button type="button" wire:click="removePhoto" wire:confirm="Retirer ce portrait ?"
                            class="link-underline text-sm text-red-600">
                        Retirer le portrait
                    </button>
                @endif

                <x-admin.field label="Remplacer la photo" for="photo-membre" :error="$errors->first('photo')">
                    <input id="photo-membre" type="file" wire:model="photo" accept="image/*"
                           class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-5 file:py-2.5 file:text-sm file:text-paper hover:file:bg-accent">
                </x-admin.field>

                <div wire:loading wire:target="photo" class="text-sm text-ink/50">Téléversement en cours…</div>
            </div>

            <x-admin.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">Enregistrer le membre</span>
                <span wire:loading wire:target="save">Enregistrement…</span>
            </x-admin.button>
        </aside>
    </form>
</div>
