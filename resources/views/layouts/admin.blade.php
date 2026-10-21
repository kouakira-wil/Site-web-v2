@php
    use App\Models\ContactMessage;
    use App\Models\Post;

    $unread = ContactMessage::query()->unread()->count();

    // Regroupement en sections, comme la maquette : un intitulé discret
    // au-dessus de chaque famille de liens.
    $groupes = [
        'Pilotage' => [
            ['route' => 'admin.dashboard', 'label' => 'Vue d’ensemble', 'match' => 'admin.dashboard', 'icon' => 'gauge'],
        ],
        'Contenus' => [
            ['route' => 'admin.projects.index', 'label' => 'Filières', 'match' => 'admin.projects.*', 'icon' => 'layers'],
            ['route' => 'admin.posts.index', 'label' => 'Actualités', 'match' => 'admin.posts.*', 'icon' => 'news'],
            ['route' => 'admin.team.index', 'label' => 'Équipe', 'match' => 'admin.team.*', 'icon' => 'users'],
            ['route' => 'admin.testimonials.index', 'label' => 'Témoignages', 'match' => 'admin.testimonials.*', 'icon' => 'quote'],
            ['route' => 'admin.pages.index', 'label' => 'En-têtes', 'match' => 'admin.pages.*', 'icon' => 'heading'],
        ],
        'Échanges' => [
            ['route' => 'admin.messages.index', 'label' => 'Messages', 'match' => 'admin.messages.*', 'icon' => 'mail', 'badge' => $unread],
            ['route' => 'admin.newsletter.index', 'label' => 'Lettre d’information', 'match' => 'admin.newsletter.*', 'icon' => 'bell'],
        ],
    ];

    $utilisateur = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Administration' }} — {{ config('app.name') }}</title>

    {{--
        Icône d'onglet, déclinée depuis favicon-dark.png. L'original fait
        1500 x 1500 pour 622 Ko : servi tel quel, il serait téléchargé à chaque
        page pour être affiché en 16 pixels.
    --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/imgs/template/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/imgs/template/logo/favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/imgs/template/logo/favicon-180.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|instrument-serif:400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-app-bg text-app-text antialiased">
    {{--
        `peer` + `peer-checked:` pilotent l'ouverture du menu sur mobile sans
        une ligne de JavaScript : la case reste hors écran, le label sert de
        bouton. Sur grand écran la barre est toujours visible.
    --}}
    <input type="checkbox" id="bascule-menu" class="peer sr-only" aria-hidden="true">

    <div class="min-h-screen lg:flex">
        {{-- Voile de fermeture, uniquement sur mobile --}}
        <label for="bascule-menu"
               class="fixed inset-0 z-30 hidden bg-app-text/40 peer-checked:block lg:!hidden"
               aria-hidden="true"></label>

        <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-app-line bg-white transition-transform duration-200 peer-checked:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-app-line px-5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="grid h-8 w-8 place-items-center rounded-md bg-brand text-sm font-bold text-white">E</span>
                    <span class="text-lg font-bold tracking-tight">{{ config('app.name') }}</span>
                </a>
            </div>

            {{-- Carte de profil --}}
            <div class="shrink-0 border-b border-app-line px-5 py-4">
                <div class="flex items-center gap-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-tile-violet text-sm font-semibold text-tile-violet-ink">
                        {{ \Illuminate\Support\Str::of($utilisateur->name)->substr(0, 1)->upper() }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">{{ $utilisateur->name }}</p>
                        <p class="flex items-center gap-1.5 text-xs text-app-muted">
                            <span class="h-1.5 w-1.5 rounded-full bg-trend-up"></span>
                            En ligne
                        </p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Navigation du back-office">
                @foreach ($groupes as $titre => $liens)
                    <p class="px-3 pb-2 pt-3 text-[11px] font-semibold uppercase tracking-[0.08em] text-app-muted">{{ $titre }}</p>
                    <ul class="space-y-0.5">
                        @foreach ($liens as $lien)
                            @php $actif = request()->routeIs($lien['match']); @endphp
                            <li>
                                <a href="{{ route($lien['route']) }}"
                                   @class([
                                       'flex items-center gap-3 rounded-md px-3 py-2.5 text-sm transition-colors duration-150',
                                       'bg-brand/10 font-semibold text-brand' => $actif,
                                       'text-app-text/70 hover:bg-app-bg hover:text-brand' => ! $actif,
                                   ])
                                   @if ($actif) aria-current="page" @endif>
                                    <x-admin.icon :name="$lien['icon']" class="h-[18px] w-[18px] shrink-0" />
                                    <span class="flex-1">{{ $lien['label'] }}</span>
                                    @if (($lien['badge'] ?? 0) > 0)
                                        <span class="rounded bg-trend-down-bg px-1.5 py-0.5 text-[11px] font-semibold text-trend-down">{{ $lien['badge'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </nav>

            <div class="shrink-0 border-t border-app-line p-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-md px-3 py-2.5 text-sm text-app-text/70 transition-colors hover:bg-app-bg hover:text-trend-down">
                        <x-admin.icon name="logout" class="h-[18px] w-[18px] shrink-0" />
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-3 border-b border-app-line bg-white px-4 lg:px-6">
                <label for="bascule-menu"
                       class="grid h-9 w-9 cursor-pointer place-items-center rounded-md text-app-muted hover:bg-app-bg hover:text-app-text lg:hidden"
                       role="button" tabindex="0" aria-controls="bascule-menu" aria-label="Ouvrir ou fermer le menu">
                    <x-admin.icon name="menu" class="h-5 w-5" />
                </label>

                <form action="{{ route('admin.search') }}" method="GET" role="search" class="hidden max-w-sm flex-1 sm:block">
                    <label for="recherche-admin" class="sr-only">Rechercher dans le back-office</label>
                    <div class="relative">
                        <x-admin.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-app-muted" />
                        <input id="recherche-admin" type="search" name="q" value="{{ request('q') }}"
                               placeholder="Rechercher…" autocomplete="off"
                               class="w-full rounded-md border-app-line bg-app-bg py-2 pl-9 pr-3 text-sm placeholder:text-app-muted focus:border-brand focus:bg-white focus:ring-0">
                    </div>
                </form>

                <div class="ml-auto flex items-center gap-1">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener"
                       class="grid h-9 w-9 place-items-center rounded-md text-app-muted transition-colors hover:bg-app-bg hover:text-app-text"
                       title="Voir le site" aria-label="Voir le site public">
                        <x-admin.icon name="external" class="h-[18px] w-[18px]" />
                    </a>

                    <a href="{{ route('admin.messages.index') }}"
                       class="relative grid h-9 w-9 place-items-center rounded-md text-app-muted transition-colors hover:bg-app-bg hover:text-app-text"
                       aria-label="Messages non lus : {{ $unread }}">
                        <x-admin.icon name="bell" class="h-[18px] w-[18px]" />
                        @if ($unread > 0)
                            <span class="absolute -right-0.5 -top-0.5 grid h-4 min-w-[16px] place-items-center rounded-full bg-trend-down px-1 text-[10px] font-semibold text-white">{{ $unread }}</span>
                        @endif
                    </a>

                    <a href="{{ route('profile.show') }}"
                       class="ml-1 flex items-center gap-2 rounded-md py-1 pl-1 pr-2 transition-colors hover:bg-app-bg">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-tile-violet text-xs font-semibold text-tile-violet-ink">
                            {{ \Illuminate\Support\Str::of($utilisateur->name)->substr(0, 1)->upper() }}
                        </span>
                        <span class="hidden text-sm font-medium sm:block">{{ \Illuminate\Support\Str::of($utilisateur->name)->explode(' ')->first() }}</span>
                    </a>
                </div>
            </header>

            <main class="min-w-0 flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
