@php
    $links = [
        ['route' => 'projects.index', 'label' => 'Réalisations'],
        ['route' => 'posts.index', 'label' => 'Journal'],
        ['route' => 'team', 'label' => 'Équipe'],
    ];
@endphp

<header
    data-site-header
    class="sticky top-0 z-40 border-b border-transparent transition-colors duration-300"
>
    <div class="shell flex h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="group flex items-center gap-3" aria-label="{{ config('app.name') }} — accueil">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-ink text-paper transition-colors duration-300 group-hover:bg-accent">
                <span class="font-display text-lg leading-none">P</span>
            </span>
            <span class="font-display text-2xl leading-none">{{ config('app.name') }}</span>
        </a>

        <nav class="hidden items-center gap-9 md:flex" aria-label="Navigation principale">
            @foreach ($links as $link)
                <a
                    href="{{ route($link['route']) }}"
                    class="link-underline text-sm font-medium tracking-wide {{ request()->routeIs(Str::before($link['route'], '.').'*') ? 'text-accent' : 'text-ink/70 hover:text-ink' }}"
                    @if (request()->routeIs(Str::before($link['route'], '.').'*')) aria-current="page" @endif
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('contact') }}"
                class="hidden rounded-full bg-ink px-6 py-3 text-sm font-medium text-paper transition-colors duration-300 hover:bg-accent sm:inline-block"
            >
                Parlons-en
            </a>

            <button
                type="button"
                data-menu-toggle
                aria-expanded="false"
                aria-controls="menu-mobile"
                class="grid h-11 w-11 place-items-center rounded-full border border-ink/20 md:hidden"
            >
                <span class="sr-only">Ouvrir le menu</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="menu-mobile" data-menu-panel hidden class="border-t border-ink/10 bg-paper md:hidden">
        <nav class="shell flex flex-col py-6" aria-label="Navigation mobile">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" class="border-b border-ink/10 py-4 font-display text-3xl">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <a href="{{ route('contact') }}" class="py-4 font-display text-3xl text-accent">Parlons-en</a>
        </nav>
    </div>
</header>
