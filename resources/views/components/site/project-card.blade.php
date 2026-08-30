@props(['project', 'eager' => false])

<article data-reveal class="group">
    <a href="{{ route('projects.show', $project) }}" class="block">
        <x-site.media-frame
            :url="$project->coverUrl()"
            :alt="'Aperçu du projet '.$project->title"
            :label="$project->title"
            :eager="$eager"
        />

        <div class="mt-5 flex items-start justify-between gap-6">
            <div>
                <h3 class="font-display text-display-sm leading-tight">{{ $project->title }}</h3>
                <p class="mt-1 text-sm text-ink/55">
                    {{ $project->category?->name ?? 'Projet' }}
                    @if ($project->year)
                        <span aria-hidden="true"> · </span>{{ $project->year }}
                    @endif
                </p>
            </div>

            <span aria-hidden="true" class="mt-2 shrink-0 text-ink/40 transition-transform duration-500 ease-out-expo group-hover:translate-x-1 group-hover:text-accent">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none">
                    <path d="M5 15 15 5M15 5H7m8 0v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
    </a>
</article>
