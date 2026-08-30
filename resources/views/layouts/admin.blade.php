@php
    use App\Models\ContactMessage;

    $unread = ContactMessage::query()->unread()->count();

    $sections = [
        ['route' => 'admin.dashboard', 'label' => 'Vue d’ensemble', 'match' => 'admin.dashboard'],
        ['route' => 'admin.projects.index', 'label' => 'Filières', 'match' => 'admin.projects.*'],
        ['route' => 'admin.posts.index', 'label' => 'Actualités', 'match' => 'admin.posts.*'],
        ['route' => 'admin.team.index', 'label' => 'Équipe', 'match' => 'admin.team.*'],
        ['route' => 'admin.messages.index', 'label' => 'Messages', 'match' => 'admin.messages.*', 'badge' => $unread],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Administration' }} — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|instrument-serif:400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-paper text-ink">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <aside class="flex shrink-0 flex-col bg-ink text-paper lg:h-screen lg:w-72 lg:sticky lg:top-0">
            <div class="flex items-center justify-between px-6 py-6 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-accent">
                        <span class="font-display text-lg leading-none text-paper">P</span>
                    </span>
                    <span class="font-display text-xl">{{ config('app.name') }}</span>
                </a>
            </div>

            <nav class="flex-1 px-3 pb-6" aria-label="Navigation du back-office">
                <ul class="space-y-1">
                    @foreach ($sections as $section)
                        @php $active = request()->routeIs($section['match']); @endphp
                        <li>
                            <a
                                href="{{ route($section['route']) }}"
                                @class([
                                    'flex items-center justify-between rounded-card px-4 py-3 text-sm transition-colors duration-200',
                                    'bg-paper/10 font-medium text-paper' => $active,
                                    'text-paper/60 hover:bg-paper/5 hover:text-paper' => ! $active,
                                ])
                                @if ($active) aria-current="page" @endif
                            >
                                <span>{{ $section['label'] }}</span>
                                @if (($section['badge'] ?? 0) > 0)
                                    <span class="rounded-full bg-accent px-2 py-0.5 text-xs font-medium text-paper">
                                        {{ $section['badge'] }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="border-t border-paper/10 px-6 py-5">
                <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-paper/50">{{ auth()->user()->email }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs">
                    <a href="{{ route('home') }}" class="text-paper/60 hover:text-paper">Voir le site</a>
                    <a href="{{ route('profile.show') }}" class="text-paper/60 hover:text-paper">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-paper/60 hover:text-accent-soft">Déconnexion</button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1 px-6 py-10 lg:px-12">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
