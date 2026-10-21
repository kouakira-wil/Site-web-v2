@extends('layouts.site')

@section('title', 'Filières et parcours — '.config('app.name'))
@section('meta_description', 'Découvrez les filières de l’ESCAT : réseaux, informatique, électromécanique, comptabilité, banque et finance.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    <x-site.page-header page="filieres" />

    @include('site.filieres.partials.index-content')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection

@push('scripts')
    {{-- Anime l'empilement des blocs « tracks ». --}}
    <script src="{{ asset('assets/js/services-details-3.js') }}"></script>
@endpush
