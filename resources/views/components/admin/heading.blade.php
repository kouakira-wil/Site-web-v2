@props([
    'title',
    'subtitle' => null,
    // Fil d'Ariane : ['Libellé' => url, 'Page courante' => null]
    'breadcrumb' => [],
])

{{--
    En-tête de page du back-office : titre en capitales à gauche, fil d'Ariane
    à droite, comme la maquette. Les actions passent sous le titre pour ne pas
    entrer en concurrence avec le fil d'Ariane.
--}}
<header class="mb-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-base font-semibold uppercase tracking-[0.04em]">{{ $title }}</h1>

        @if ($breadcrumb !== [])
            <nav aria-label="Fil d’Ariane">
                <ol class="flex flex-wrap items-center gap-1.5 text-[13px] text-app-muted">
                    @foreach ($breadcrumb as $libelle => $url)
                        <li class="flex items-center gap-1.5">
                            @if ($url)
                                <a href="{{ $url }}" class="hover:text-brand">{{ $libelle }}</a>
                                <span aria-hidden="true" class="text-app-line">&rsaquo;</span>
                            @else
                                <span aria-current="page" class="text-app-text">{{ $libelle }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>

    @if ($subtitle || isset($actions))
        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
            @if ($subtitle)
                <p class="text-[13px] text-app-muted">{{ $subtitle }}</p>
            @endif

            @if (isset($actions))
                <div class="ml-auto flex flex-wrap items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif
</header>
