<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Studio de création : identité visuelle, sites web et direction artistique.')">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', 'Studio de création : identité visuelle, sites web et direction artistique.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif

    <link rel="canonical" href="{{ url()->current() }}">
    {{--
        Icône d'onglet, déclinée depuis favicon-dark.png. L'original fait
        1500 x 1500 pour 622 Ko : servi tel quel, il serait téléchargé à chaque
        page pour être affiché en 16 pixels.
    --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/imgs/template/logo/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/imgs/template/logo/favicon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/imgs/template/logo/favicon-180.png') }}">

    {{--
        Template stylesheets. Tailwind sits exactly where bootstrap.min.css used
        to: the template's own rules (main.css, spacing.css) were written to
        override the grid framework, so they must still load after it. Utilities
        that have to win regardless are emitted with Tailwind's `!` modifier,
        mirroring the !important Bootstrap put on its own utility classes.
    --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/carouselTicker.css') }}">

    {{-- Tailwind (utilities only, preflight disabled — see tailwind.site.config.js). --}}
    @vite('resources/css/site.css')

    <link rel="stylesheet" href="{{ asset('assets/css/vendors/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    {{-- Couleurs de marque : doit passer APRÈS main.css pour le surcharger. --}}
    @vite('resources/css/brand.css')

    @stack('head')
</head>

<body class="at-magic-cursor">
    {{-- En tête de corps : le voile doit couvrir dès le premier rendu. --}}
    @include('site.partials.preloader')

    @include('site.partials.search-form')

    {{-- The home page and the inner pages use different header variants. --}}
    @hasSection('header')
        @yield('header')
    @else
        @include('site.partials.header')
    @endif

    @include('site.partials.sticky-menu')
    @include('site.partials.offcanvas')
    @include('site.partials.offcanvas-2')
    @include('site.partials.cursor')

    <div id="smooth-wrapper">
        {{--
            Both wrappers carry per-page classes in the original template: the
            inner pages need `z-index-3` so the content scrolls above the fixed
            footer, and some pages scope their styles through a class on <main>
            (for example `team2-page`).
        --}}
        <div id="smooth-content" class="@yield('smooth_class')">
            <main class="@yield('main_class', 'bg-neutral-0')">
                @yield('content')
            </main>

            {{--
                Toutes les pages utilisent le pied de page à révélation
                (`footer-inner`) ; le repli le reprend pour qu'une page qui
                oublierait la section reste cohérente. L'ancien pied de page
                d'accueil reste dans site/partials/footer.blade.php.
            --}}
            @hasSection('footer')
                @yield('footer')
            @else
                @include('site.partials.footer-inner')
            @endif
        </div>
    </div>

    @include('site.partials.back-to-top')

    {{-- Libs JS, minus bootstrap.min.js. GSAP and ScrollSmoother ship inside plugin.js. --}}
    <script src="{{ asset('assets/js/vendors/color-modes.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/plugin.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery.carouselTicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery.appear.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/jquery.elevatezoom.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/nice-select.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/parallax.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/ripple-2.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/image-hover-effects.js') }}"></script>
    <script src="{{ asset('assets/js/at-cursor.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- Replaces Bootstrap's collapse plugin (accordions). --}}
    @vite('resources/js/site.js')

    @stack('scripts')
</body>
</html>
