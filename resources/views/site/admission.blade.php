@extends('layouts.site')

@section('title', 'Admission — '.config('app.name'))
@section('meta_description', 'Conditions d’accès aux cycles de l’ESCAT, étapes du dossier, programmes et réponses aux questions fréquentes.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    <x-site.page-header page="admission" />

    @include('site.admission.criteres')
    @include('site.admission.processus')
    @include('site.admission.programmes')
    @include('site.admission.faq')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
