@extends('layouts.site')

@section('title', 'L’école - '.config('app.name'))
@section('meta_description', 'L’ESCAT, École Supérieure de Commerce, d’Administration et de Technologie : notre histoire, nos pôles et notre équipe, à Brazzaville.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    <x-site.page-header page="about" />

    @include('site.about.intro')
    @include('site.about.reperes')
    @include('site.about.vision')
    @include('site.about.mission')
    {{-- @include('site.about.chiffres') --}}
    @include('site.about.identite')
    @include('site.about.directeur')
    @include('site.about.parcours')
    {{--
        La section « distinctions » du gabarit attend des prix et des dates que
        l'ESCAT ne publie pas. Le partial reste disponible dans site/about/ :
        il suffit de le renseigner puis de l'inclure ici.
    --}}
    @include('site.about.equipe')
    {{-- @include('site.about.contact') --}}
    @include('site.about.partenaires')
    @include('site.about.newsletter')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
