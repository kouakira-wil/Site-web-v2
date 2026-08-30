@extends('layouts.site')

@section('title', 'Contact et admissions — '.config('app.name'))
@section('meta_description', 'Contactez le service des admissions de l’ESCAT : +242 06 884 83 44, infos@escat.org.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.contact')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
