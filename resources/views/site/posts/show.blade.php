@extends('layouts.site')

@section('title', 'Actualité — '.config('app.name'))
@section('meta_description', 'Actualité de l’ESCAT.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.post-show')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
