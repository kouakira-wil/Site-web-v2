@props([
    'post',
    'fallback' => null,
    'class' => 'lg:w-1/4 md:w-1/2 w-full mb-30',
])

{{--
    Carte d'actualité du gabarit, alimentée par la base.
    `fallback` sert d'illustration tant qu'aucune image de couverture n'est
    renseignée, pour ne pas laisser un cadre vide.
--}}
<div class="blog-card {{ $class }}">
    <div class="blog-card__thumb hover-effect-1">
        <a href="{{ route('posts.show', $post) }}" class="blog-card__img-link">
            <img src="{{ $post->coverUrl() ?? $fallback }}"
                 class="blog-card__img22"
                 alt="{{ $post->title }}"
                 loading="lazy" decoding="async">
        </a>
    </div>
    <div class="blog-card__content">
        <h2 class="h6 blog-card__title">
            <a href="{{ route('posts.show', $post) }}" class="blog-card__title-link">{{ $post->title }}</a>
        </h2>
        <p class="blog-card__meta">
            <time class="blog-card__meta-text" datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d F Y') }}</time>
        </p>
    </div>
</div>
