@extends('layouts.site')

@section('title', $member->name.' — '.config('app.name'))
@section('meta_description', $member->role.' à l’ESCAT, '.config('app.name').'.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    <section class="sd3-tracks pt-150 pb-40">
        <div class="container">
            <nav class="sd3-crumb pb-30" aria-label="Fil d’Ariane">
                {{-- La page Équipe a cédé sa place à Admission : le fil remonte
                     à l'accueil, seul parent qui reste à cette fiche. --}}
                <a href="{{ route('home') }}">Accueil</a>
                <span>/</span>
                <span>{{ $member->name }}</span>
            </nav>

            <div class="sd3-tracks__head">
                <p class="sd3-label mb-15">{{ $member->role }}</p>
                <h1 class="sd3-tracks__title reveal-text !mb-0">{{ $member->name }}</h1>
            </div>
        </div>

        <div class="container pt-40">
            <article class="sd3-track">
                <div class="sd3-track__meta">
                    <span>Équipe</span>
                    <strong>ESCAT</strong>
                </div>

                <div class="sd3-track__body">
                    @if ($member->bio)
                        <p>{{ $member->bio }}</p>
                    @endif

                    <ul>
                        @if ($member->email)
                            <li><a href="mailto:{{ $member->email }}">{{ $member->email }}</a></li>
                        @endif
                        @foreach ($member->socials ?? [] as $reseau => $url)
                            @if (filled($url))
                                <li>
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ Str::ucfirst($reseau) }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="sd3-track__media">
                    @if ($member->photoUrl())
                        <img src="{{ $member->photoUrl() }}" alt="Portrait de {{ $member->name }}"
                             width="560" height="420" loading="lazy" decoding="async">
                    @else
                        <span aria-hidden="true" class="sd3-track__placeholder"></span>
                    @endif
                </div>
            </article>
        </div>
    </section>

    @if ($others->isNotEmpty())
        <section class="sd3-tracks pt-20 pb-100" aria-labelledby="titre-autres-membres">
            <div class="container">
                <div class="sd3-tracks__head">
                    <p class="sd3-label mb-15">[02] Équipe</p>
                    <h2 id="titre-autres-membres" class="sd3-tracks__title !mb-0">Les autres membres</h2>
                </div>

                <div class="flex flex-wrap -mx-3 [&>*]:px-3 pt-40">
                    @foreach ($others as $autre)
                        <div class="w-full md:w-1/2 xl:w-1/3 mb-40">
                            <a href="{{ route('team.show', $autre) }}" class="team-card !block">
                                <div class="team-card-image">
                                    @if ($autre->photoUrl())
                                        <img src="{{ $autre->photoUrl() }}" alt="Portrait de {{ $autre->name }}" loading="lazy">
                                    @endif
                                </div>
                                <div class="team-card-content">
                                    <h3 class="team-card-name">{{ $autre->name }}</h3>
                                    <p class="team-card-position">{{ $autre->role }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
