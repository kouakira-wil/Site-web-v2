<div>
    <x-admin.heading
        title="Vue d’ensemble"
        :subtitle="'Bonjour '.auth()->user()->name.', voici l’état du site.'"
        :breadcrumb="['Administration' => route('admin.dashboard'), 'Vue d’ensemble' => null]"
    >
        <x-slot:actions>
            <x-admin.button variant="secondary" :href="route('admin.posts.create')">Nouvelle actualité</x-admin.button>
            <x-admin.button :href="route('admin.projects.create')">Nouvelle filière</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    <div class="grid gap-4 xl:grid-cols-12">
        {{-- Colonne principale --}}
        <div class="space-y-4 xl:col-span-8">
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($stats as $stat)
                    <x-admin.stat
                        :label="$stat['label']"
                        :value="$stat['value']"
                        :caption="$stat['caption']"
                        :icon="$stat['icon']"
                        :tone="$stat['tone']"
                        :trend="$stat['trend']"
                        :href="$stat['route']"
                    />
                @endforeach
            </div>

            @php
                $maximum = collect($historique)->flatMap(fn (array $m): array => [$m['actualites'], $m['filieres']])->max() ?: 1;
                $totalActualites = collect($historique)->sum('actualites');
                $totalFilieres = collect($historique)->sum('filieres');
            @endphp

            <x-admin.card title="Publications sur 12 mois" body-class="p-0">
                <x-slot:actions>
                    <span class="text-[13px] text-app-muted">{{ $totalActualites + $totalFilieres }} publication(s)</span>
                </x-slot:actions>

                {{-- Bandeau de chiffres, comme la maquette --}}
                <div class="grid grid-cols-2 divide-x divide-app-line border-b border-app-line sm:grid-cols-4">
                    <div class="px-5 py-4 text-center">
                        <p class="text-xl font-semibold">{{ $totalActualites }}</p>
                        <p class="mt-1 text-[13px] text-app-muted">Actualités</p>
                    </div>
                    <div class="px-5 py-4 text-center">
                        <p class="text-xl font-semibold">{{ $totalFilieres }}</p>
                        <p class="mt-1 text-[13px] text-app-muted">Filières</p>
                    </div>
                    <div class="px-5 py-4 text-center">
                        <p class="text-xl font-semibold">{{ $aVenir->count() }}</p>
                        <p class="mt-1 text-[13px] text-app-muted">Programmées</p>
                    </div>
                    <div class="px-5 py-4 text-center">
                        <p class="text-xl font-semibold text-trend-up">{{ $maximum }}</p>
                        <p class="mt-1 text-[13px] text-app-muted">Meilleur mois</p>
                    </div>
                </div>

                {{--
                    Histogramme en CSS : les hauteurs sont calculées côté serveur,
                    aucune librairie de graphiques n'est chargée. Le tableau qui
                    suit porte les mêmes chiffres pour les lecteurs d'écran.
                --}}
                <div class="overflow-x-auto p-5">
                    <div class="flex min-w-[520px] items-end gap-3" role="presentation">
                        @foreach ($historique as $mois)
                            <div class="flex flex-1 flex-col items-center gap-2">
                                <div class="flex h-40 w-full items-end justify-center gap-1">
                                    <span class="w-1/3 rounded-t bg-tile-violet-ink/85"
                                          style="height: {{ max($mois['actualites'] / $maximum * 100, $mois['actualites'] > 0 ? 4 : 0) }}%"></span>
                                    <span class="w-1/3 rounded-t bg-tile-amber-ink"
                                          style="height: {{ max($mois['filieres'] / $maximum * 100, $mois['filieres'] > 0 ? 4 : 0) }}%"></span>
                                </div>
                                <span class="text-[11px] text-app-muted">{{ $mois['mois'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-center gap-5 text-[13px] text-app-muted">
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-tile-violet-ink/85"></span>Actualités</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-tile-amber-ink"></span>Filières</span>
                    </div>

                    <table class="sr-only">
                        <caption>Publications par mois sur les douze derniers mois</caption>
                        <thead><tr><th>Mois</th><th>Actualités</th><th>Filières</th></tr></thead>
                        <tbody>
                            @foreach ($historique as $mois)
                                <tr><td>{{ $mois['mois'] }}</td><td>{{ $mois['actualites'] }}</td><td>{{ $mois['filieres'] }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>

        {{-- Colonne latérale : calendrier et parutions à venir --}}
        <div class="xl:col-span-4">
            <x-admin.card body-class="p-0" class="xl:sticky xl:top-20">
                <x-slot:title>Calendrier des parutions</x-slot:title>
                <x-slot:actions>
                    <span class="text-[13px] font-medium capitalize text-app-muted">{{ $calendrier['mois'] }}</span>
                </x-slot:actions>

                <div class="p-5">
                    <table class="w-full table-fixed border-collapse">
                        <caption class="sr-only">Publications du mois en cours, par jour</caption>
                        <thead>
                            <tr>
                                @foreach (['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'] as $jour)
                                    <th scope="col" class="pb-2 text-center text-[11px] font-semibold uppercase text-app-muted">{{ $jour }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calendrier['semaines'] as $semaine)
                                <tr>
                                    @foreach ($semaine as $case)
                                        <td class="p-0.5 text-center">
                                            @if ($case['jour'])
                                                <span @class([
                                                    'relative mx-auto grid h-9 w-9 place-items-center rounded-md text-[13px]',
                                                    'bg-trend-up font-semibold text-white' => $case['aujourdhui'],
                                                    'bg-tile-blue font-medium text-tile-blue-ink' => ! $case['aujourdhui'] && $case['publications'] > 0,
                                                    'text-app-text/70' => ! $case['aujourdhui'] && $case['publications'] === 0,
                                                ])>
                                                    {{ $case['jour'] }}
                                                    @if ($case['publications'] > 0)
                                                        <span class="sr-only">, {{ $case['publications'] }} publication(s)</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-app-line px-5 py-4">
                    <p class="mb-3 text-[11px] font-semibold uppercase tracking-[0.08em] text-app-muted">Parutions programmées</p>

                    @forelse ($aVenir as $article)
                        <a href="{{ route('admin.posts.edit', $article) }}" class="group flex items-center gap-3 py-2.5">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-tile-cyan text-sm font-semibold text-tile-cyan-ink">
                                {{ $article->published_at->format('d') }}
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium group-hover:text-brand">{{ $article->title }}</span>
                                <span class="block text-xs text-app-muted">{{ $article->published_at->translatedFormat('d F Y') }}</span>
                            </span>
                            <span class="shrink-0 text-xs text-app-muted">{{ $article->published_at->format('H:i') }}</span>
                        </a>
                    @empty
                        <p class="py-2 text-[13px] text-app-muted">Aucune parution programmée.</p>
                    @endforelse

                    <a href="{{ route('admin.posts.index') }}" class="mt-2 block text-center text-[13px] font-medium text-brand hover:underline">
                        Voir toutes les actualités
                    </a>
                </div>
            </x-admin.card>
        </div>
    </div>

    {{-- Bas de page : deux tableaux --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <x-admin.card title="Derniers messages" body-class="p-0">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('admin.messages.index')">Tout voir</x-admin.button>
            </x-slot:actions>

            @forelse ($latestMessages as $message)
                <article class="flex items-start justify-between gap-4 border-b border-app-line px-5 py-3.5 last:border-b-0">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium">{{ $message->name }}</p>
                        <p class="truncate text-[13px] text-app-muted">{{ $message->subject ?: 'Sans sujet' }}</p>
                        <p class="mt-1 text-xs text-app-muted">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                    <x-admin.status-pill :status="$message->status" />
                </article>
            @empty
                <p class="px-5 py-10 text-center text-[13px] text-app-muted">Aucun message reçu pour le moment.</p>
            @endforelse
        </x-admin.card>

        <x-admin.card title="Filières en brouillon" body-class="p-0">
            <x-slot:actions>
                <x-admin.button variant="secondary" :href="route('admin.projects.index')">Tout voir</x-admin.button>
            </x-slot:actions>

            @forelse ($draftProjects as $project)
                <a href="{{ route('admin.projects.edit', $project) }}"
                   class="group flex items-center justify-between gap-4 border-b border-app-line px-5 py-3.5 last:border-b-0 hover:bg-app-bg">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium group-hover:text-brand">{{ $project->title }}</p>
                        <p class="text-xs text-app-muted">Modifié {{ $project->updated_at->diffForHumans() }}</p>
                    </div>
                    <span aria-hidden="true" class="shrink-0 text-app-muted group-hover:text-brand">&rarr;</span>
                </a>
            @empty
                <p class="px-5 py-10 text-center text-[13px] text-app-muted">Aucune filière en brouillon.</p>
            @endforelse
        </x-admin.card>
    </div>
</div>
