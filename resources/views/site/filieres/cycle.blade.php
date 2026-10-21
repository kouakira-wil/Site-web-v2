@extends('layouts.site')

@section('title', $cycle->name.' — '.config('app.name'))
@section('meta_description', 'Les filières de l’ESCAT ouvertes en '.$cycle->name.'.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    {{--
        Le bandeau de la page Filières, mais aux couleurs du cycle : mêmes
        visuel et mise en page, textes repris du cycle lui-même.
    --}}
    <x-site.page-header
        page="filieres"
        :eyebrow="$cycle->short_label"
        :title="$cycle->name"
        :intro="$cycle->description"
        :tags="$poles->pluck('name')->all()"
        :crumb-label="$cycle->name"
        crumb-parent="Filières"
        :crumb-parent-url="route('projects.index')"
    />

    @include('site.filieres.partials.cycle-content')
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
