<div>
    <x-admin.heading
        title="Recherche"
        :subtitle="$terme !== '' ? 'Résultats pour « '.$terme.' »' : 'Retrouvez un contenu, publié ou en brouillon.'"
        :breadcrumb="['Administration' => route('admin.dashboard'), 'Recherche' => null]"
    />

    <x-admin.card class="mb-4">
        <label for="recherche-terme" class="mb-2 block text-xs font-semibold uppercase tracking-[0.06em] text-app-muted">
            Votre recherche
        </label>
        <div class="relative max-w-xl">
            <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-app-muted" />
            <input id="recherche-terme" type="search" wire:model.live.debounce.300ms="q"
                   placeholder="Une filière, une actualité, un message…" autocomplete="off"
                   class="w-full rounded-md border-app-line bg-white py-2.5 pl-9 pr-3 text-sm placeholder:text-app-muted focus:border-brand focus:ring-0">
        </div>
    </x-admin.card>

    @php
        $groupes = [
            ['titre' => 'Filières', 'items' => $filieres, 'icon' => 'layers', 'tone' => 'bg-tile-blue text-tile-blue-ink'],
            ['titre' => 'Actualités', 'items' => $actualites, 'icon' => 'news', 'tone' => 'bg-tile-amber text-tile-amber-ink'],
            ['titre' => 'Équipe', 'items' => $membres, 'icon' => 'users', 'tone' => 'bg-tile-cyan text-tile-cyan-ink'],
            ['titre' => 'Messages', 'items' => $messages, 'icon' => 'mail', 'tone' => 'bg-tile-violet text-tile-violet-ink'],
        ];

        $total = collect($groupes)->sum(fn (array $g): int => $g['items']->count());
    @endphp

    @if ($terme === '')
        <x-admin.card>
            <p class="py-6 text-center text-[13px] text-app-muted">Saisissez un mot-clé pour lancer la recherche.</p>
        </x-admin.card>
    @elseif (! $assezLong)
        <x-admin.card>
            <p class="py-6 text-center text-[13px] text-app-muted" role="status">
                Merci de saisir au moins {{ $longueurMinimale }} caractères.
            </p>
        </x-admin.card>
    @elseif ($total === 0)
        <x-admin.card>
            <p class="py-6 text-center text-[13px] text-app-muted" role="status">
                Aucun résultat pour « {{ $terme }} ».
            </p>
        </x-admin.card>
    @else
        <p class="mb-4 text-[13px] text-app-muted" role="status" aria-live="polite">
            {{ $total }} résultat{{ $total > 1 ? 's' : '' }} pour « {{ $terme }} ».
        </p>

        <div class="grid gap-4 lg:grid-cols-2">
            @foreach ($groupes as $groupe)
                @continue($groupe['items']->isEmpty())

                <x-admin.card body-class="p-0">
                    <x-slot:title>{{ $groupe['titre'] }} ({{ $groupe['items']->count() }})</x-slot:title>

                    @foreach ($groupe['items'] as $item)
                        @php
                            $lien = match ($groupe['titre']) {
                                'Filières' => route('admin.projects.edit', $item),
                                'Actualités' => route('admin.posts.edit', $item),
                                'Équipe' => route('admin.team.edit', $item),
                                default => route('admin.messages.index'),
                            };

                            $titre = $item->title ?? $item->name;
                            $detail = match ($groupe['titre']) {
                                'Messages' => $item->subject ?: 'Sans sujet',
                                'Équipe' => $item->role,
                                default => $item->excerpt,
                            };
                        @endphp

                        <a href="{{ $lien }}"
                           class="group flex items-center gap-3 border-b border-app-line px-5 py-3.5 last:border-b-0 hover:bg-app-bg">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-md {{ $groupe['tone'] }}">
                                <x-admin.icon :name="$groupe['icon']" class="h-[18px] w-[18px]" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium group-hover:text-brand">{{ $titre }}</span>
                                @if (filled($detail))
                                    <span class="block truncate text-[13px] text-app-muted">{{ \Illuminate\Support\Str::limit($detail, 90) }}</span>
                                @endif
                            </span>
                            @isset($item->status)
                                <x-admin.status-pill :status="$item->status" />
                            @endisset
                        </a>
                    @endforeach
                </x-admin.card>
            @endforeach
        </div>
    @endif
</div>
