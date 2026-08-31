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
    @include('site.filieres.partials.show-programme')
    @include('site.filieres.partials.show-modules')

    @if ($project->images->isNotEmpty())
        <section class="sd3-tracks pt-20 pb-40" aria-labelledby="titre-galerie-filiere">
            <div class="container">
                <div class="sd3-tracks__head">
                    <p class="sd3-label mb-15">[04] En images</p>
                    <h2 id="titre-galerie-filiere" class="sd3-tracks__title !mb-0">La filière en images</h2>
                </div>

                <div class="pt-40">
                    <x-site.gallery :images="$project->images" :label="'Galerie de la filière : '.$project->title" />
                </div>
            </div>
        </section>
    @endif
    @include('site.filieres.partials.show-cta')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/services-details-3.js') }}"></script>
@endpush
