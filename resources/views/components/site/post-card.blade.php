@props(['post'])

<article data-reveal class="group flex flex-col">
    <a href="{{ route('posts.show', $post) }}" class="flex flex-1 flex-col">
        <x-site.media-frame
            :url="$post->coverUrl()"
            :alt="'Illustration de l’article '.$post->title"
            :label="$post->title"
            ratio="aspect-[16/10]"
        />

        <div class="mt-5 flex flex-1 flex-col">
            <p class="text-xs uppercase tracking-[0.18em] text-accent">
                {{ $post->category?->name ?? 'Journal' }}
            </p>

            <h3 class="mt-3 font-display text-2xl leading-snug text-balance group-hover:text-accent">
                {{ $post->title }}
            </h3>

            @if ($post->excerpt)
                <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-ink/65">{{ $post->excerpt }}</p>
            @endif

            <p class="mt-4 pt-4 text-xs text-ink/45">
                <time datetime="{{ $post->published_at?->toDateString() }}">
                    {{ $post->published_at?->translatedFormat('d F Y') }}
                </time>
                <span aria-hidden="true"> · </span>{{ $post->reading_minutes }} min de lecture
            </p>
        </div>
    </a>
</article>
