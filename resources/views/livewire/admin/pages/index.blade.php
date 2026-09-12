<div>
    <x-admin.heading title="Textes du site"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Textes du site' => null]"
                     subtitle="Chaque bloc s’édite sur sa propre page." />

    <div class="space-y-4">
        @foreach ($familles as $groupe => $blocs)
            <x-admin.card body-class="p-0">
                <x-slot:title>{{ $intitules[$groupe] ?? $groupe }}</x-slot:title>
                <x-slot:actions>
                    <span class="text-[13px] text-app-muted">{{ $blocs->count() }} bloc{{ $blocs->count() > 1 ? 's' : '' }}</span>
                </x-slot:actions>

                @foreach ($blocs as $bloc)
                    <a href="{{ route('admin.pages.edit', $bloc) }}"
                       wire:key="bloc-{{ $bloc->id }}"
                       class="group flex flex-wrap items-center justify-between gap-4 border-b border-app-line px-5 py-3.5 last:border-b-0 hover:bg-app-bg">
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium group-hover:text-brand">{{ $bloc->label }}</span>
                            <span class="block truncate text-[13px] text-app-muted">
                                {{ $bloc->title }}
                                @if (filled($bloc->title_accent)) · {{ $bloc->title_accent }} @endif
                            </span>
                        </span>

                        <span class="flex shrink-0 items-center gap-3">
                            @if (filled($bloc->image))
                                <span class="rounded bg-tile-blue px-2 py-1 text-[11px] font-semibold text-tile-blue-ink">Visuel</span>
                            @endif

                            @if (blank($bloc->intro) && blank($bloc->body) && $bloc->tagList() === [])
                                {{-- Signale les blocs qui n'affichent encore rien sur le site. --}}
                                <span class="rounded bg-app-bg px-2 py-1 text-[11px] font-semibold text-app-muted">À remplir</span>
                            @endif

                            <span aria-hidden="true" class="text-app-muted group-hover:text-brand">&rarr;</span>
                        </span>
                    </a>
                @endforeach
            </x-admin.card>
        @endforeach
    </div>
</div>
