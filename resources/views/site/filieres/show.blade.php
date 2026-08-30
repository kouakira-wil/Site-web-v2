@extends('layouts.site')

@section('title', $project->title.' — '.config('app.name'))
@section('meta_description', $project->excerpt ?? 'Filière enseignée à l’ESCAT.')
@section('og_type', 'article')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    @include('site.filieres.partials.show-hero')
    @include('site.filieres.partials.show-apercu')
    @include('site.filieres.partials.show-parcours')
    @include('site.filieres.partials.show-modules')
    @include('site.filieres.partials.show-cta')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/services-details-3.js') }}"></script>
@endpush
