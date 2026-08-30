@extends('layouts.site')

@section('title', 'Filière — '.config('app.name'))
@section('meta_description', 'Programme, débouchés et conditions d’admission de la filière.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-50')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.project-show')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
