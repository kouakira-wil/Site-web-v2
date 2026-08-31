@extends('layouts.site')

@section('title', ($terme !== '' ? 'Recherche : '.$terme : 'Recherche').' — '.config('app.name'))
@section('meta_description', 'Rechercher une filière, une actualité ou un membre de l’équipe sur le site de l’ESCAT.')
@section('smooth_class', 'z-index-3')
@section('main_class', 'bg-neutral-0 sd3-page')

@section('header')
    @include('site.partials.header-inner')
@endsection

@section('content')
    <x-site.page-header page="recherche" />

    <div class="sec-1-archive-1 !overflow-hidden pb-100">
        <div class="container">
            {{-- Le formulaire est repris ici pour affiner sans rouvrir la fenêtre de recherche. --}}
            <form action="{{ route('search') }}" method="GET" role="search" class="search-page__form">
                <label for="recherche-terme" class="search-page__label">Votre recherche</label>
                <div class="search-page__row">
                    <input id="recherche-terme" type="search" name="q" value="{{ $terme }}"
                           placeholder="Une filière, un cycle, une actualité…"
                           class="search-page__input" autocomplete="off">
                    <button type="submit" class="at-btn">Rechercher</button>
                </div>
            </form>

            @php
                $total = $filieres->count() + $actualites->count() + $membres->count();
            @endphp

            @if ($terme === '')
                <p class="search-page__note">Saisissez un mot-clé pour lancer la recherche.</p>
            @elseif (! $assezLong)
                <p class="search-page__note" role="status">Merci de saisir au moins {{ $longueurMinimale }} caractères.</p>
            @else
                <p class="search-page__note" role="status" aria-live="polite">
                    @if ($total === 0)
                        Aucun résultat pour « {{ $terme }} ». Essayez un terme plus général, ou
                        <a href="{{ route('contact') }}">écrivez-nous</a>.
                    @else
                        {{ $total }} résultat{{ $total > 1 ? 's' : '' }} pour « {{ $terme }} ».
                    @endif
                </p>
            @endif

            @if ($filieres->isNotEmpty())
                <section class="search-page__group" aria-labelledby="resultats-filieres">
                    <h2 id="resultats-filieres" class="search-page__group-title">Filières <span>{{ $filieres->count() }}</span></h2>
                    <ul class="search-page__list">
                        @foreach ($filieres as $filiere)
                            <li class="search-page__item">
                                <a href="{{ route('projects.show', $filiere) }}" class="search-page__link">
                                    <span class="search-page__item-title">{{ $filiere->title }}</span>
                                    @if (filled($filiere->excerpt))
                                        <span class="search-page__item-text">{{ Str::limit($filiere->excerpt, 150) }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($actualites->isNotEmpty())
                <section class="search-page__group" aria-labelledby="resultats-actualites">
                    <h2 id="resultats-actualites" class="search-page__group-title">Actualités <span>{{ $actualites->count() }}</span></h2>
                    <ul class="search-page__list">
                        @foreach ($actualites as $actualite)
                            <li class="search-page__item">
                                <a href="{{ route('posts.show', $actualite) }}" class="search-page__link">
                                    <span class="search-page__item-title">{{ $actualite->title }}</span>
                                    <span class="search-page__item-text">
                                        <time datetime="{{ $actualite->published_at?->toDateString() }}">{{ $actualite->published_at?->translatedFormat('d F Y') }}</time>
                                        @if (filled($actualite->excerpt)) — {{ Str::limit($actualite->excerpt, 130) }} @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($membres->isNotEmpty())
                <section class="search-page__group" aria-labelledby="resultats-equipe">
                    <h2 id="resultats-equipe" class="search-page__group-title">Équipe <span>{{ $membres->count() }}</span></h2>
                    <ul class="search-page__list">
                        @foreach ($membres as $membre)
                            <li class="search-page__item">
                                <a href="{{ route('team.show', $membre) }}" class="search-page__link">
                                    <span class="search-page__item-title">{{ $membre->name }}</span>
                                    @if (filled($membre->role))
                                        <span class="search-page__item-text">{{ $membre->role }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('footer')
    @include('site.partials.footer-inner')
@endsection
