@extends('layouts.site')

@section('title', 'Page introuvable — '.config('app.name'))
@section('meta_description', 'La page demandée n’existe pas.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.not-found')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/matter.js') }}"></script>
    <script src="{{ asset('assets/js/throwable.js') }}"></script>
@endpush
