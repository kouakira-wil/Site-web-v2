@extends('layouts.site')

@section('title', 'ESCAT - École Supérieure de Commerce, d’Administration et de Technologie')
@section('meta_description', 'L’ESCAT forme à Brazzaville les techniciens et cadres de demain : CAP, Licence et Master, en Technologie et en Gestion.')
@section('smooth_class', 'z-index-3')

@section('content')
    @include('site.home.hero')
    @include('site.home.about')
    @include('site.home.testimonials')
    @include('site.home.portfolio')
    @include('site.home.brand')
    @include('site.home.services')
    @include('site.home.showcase')
    @include('site.home.marquee')
    @include('site.home.process')
    @include('site.home.clients')
    @include('site.home.faq')
    @include('site.home.banner')
    @include('site.home.journal')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
