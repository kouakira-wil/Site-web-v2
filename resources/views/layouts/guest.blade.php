<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Même icône d'onglet que le reste du site. --}}
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/imgs/template/logo/favicon-32.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/imgs/template/logo/favicon-192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/imgs/template/logo/favicon-180.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
