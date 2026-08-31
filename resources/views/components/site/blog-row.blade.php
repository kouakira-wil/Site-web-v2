@props([
    'post',
    'fallback' => null,
])

{{-- Variante « ligne » de la carte d'actualité (blog-card-2 du gabarit). --}}
<article class="blog-card-2 bg-neutral-0 !py-12">
    <div class="blog-card-2__left flex-grow-1">
        <span class="blog-card-2__category">{{ $post->category?->name ?? 'Actualité' }}</span>
        <h4 class="h6 blog-card-2__title">
            <a href="{{ route('posts.show', $post) }}" class="blog-card-2__title-link">{{ $post->title }}</a>
        </h4>
        <p class="blog-card-2__meta">
            <time class="blog-card-2__meta-text" datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d F Y') }}</time>
        </p>
    </div>
    <div class="blog-card-2__thumb hover-effect-1">
        <a href="{{ route('posts.show', $post) }}" class="blog-card-2__img-link">
            <img src="{{ $post->coverUrl() ?? $fallback }}"
                 class="blog-card-2__img"
                 alt="{{ $post->title }}"
                 loading="lazy" decoding="async">
        </a>
    </div>
</article>
