@extends('layouts.site')

@section('title', 'Équipe pédagogique — '.config('app.name'))
@section('meta_description', 'Enseignants, encadrants et personnel administratif de l’ESCAT.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 team2-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.pages.team')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/team-2.js') }}"></script>
@endpush
