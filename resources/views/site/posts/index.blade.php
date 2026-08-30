@extends('layouts.site')

@section('title', 'Actualités — '.config('app.name'))
@section('meta_description', 'Actualités, événements et vie étudiante de l’ESCAT à Brazzaville.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.posts-index')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
