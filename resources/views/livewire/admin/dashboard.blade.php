<div>
    <x-admin.heading
        title="Vue d’ensemble"
        :subtitle="'Bonjour '.auth()->user()->name.', voici l’état du site.'"
    >
        <x-slot:actions>
            <x-admin.button variant="secondary" :href="route('admin.posts.create')">Nouvelle actualité</x-admin.button>
            <x-admin.button :href="route('admin.projects.create')">Nouvelle filière</x-admin.button>
        </x-slot:actions>
    </x-admin.heading>

    <div class="grid gap-px overflow-hidden rounded-card bg-ink/10 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <a href="{{ $stat['route'] }}" class="group bg-paper p-6 transition-colors duration-200 hover:bg-paper-warm">
                <p class="text-xs uppercase tracking-[0.14em] text-ink/45">{{ $stat['label'] }}</p>
                <p class="mt-3 font-display text-5xl group-hover:text-accent">{{ $stat['value'] }}</p>
                <p class="mt-2 text-sm text-ink/50">{{ $stat['detail'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-12 grid gap-10 lg:grid-cols-2">
        <section aria-labelledby="titre-derniers-messages">
            <div class="flex items-center justify-between border-b border-ink/15 pb-4">
                <h2 id="titre-derniers-messages" class="font-display text-2xl">Derniers messages</h2>
                <a href="{{ route('admin.messages.index') }}" class="link-underline text-sm text-ink/55 hover:text-ink">
                    Tout voir
                </a>
            </div>

            @forelse ($latestMessages as $message)
                <article class="flex items-start justify-between gap-4 border-b border-ink/10 py-4">
                    <div class="min-w-0">
                        <p class="truncate font-medium">{{ $message->name }}</p>
                        <p class="truncate text-sm text-ink/55">{{ $message->subject ?: 'Sans sujet' }}</p>
                        <p class="mt-1 text-xs text-ink/40">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                    <x-admin.status-pill :status="$message->status" />
                </article>
            @empty
                <p class="py-10 text-sm text-ink/50">Aucun message reçu pour le moment.</p>
            @endforelse
        </section>

        <section aria-labelledby="titre-brouillons">
            <div class="flex items-center justify-between border-b border-ink/15 pb-4">
                <h2 id="titre-brouillons" class="font-display text-2xl">Filières en brouillon</h2>
                <a href="{{ route('admin.projects.index') }}" class="link-underline text-sm text-ink/55 hover:text-ink">
                    Tout voir
                </a>
            </div>

            @forelse ($draftProjects as $project)
                <a href="{{ route('admin.projects.edit', $project) }}" class="flex items-center justify-between gap-4 border-b border-ink/10 py-4 hover:text-accent">
                    <div class="min-w-0">
                        <p class="truncate font-medium">{{ $project->title }}</p>
                        <p class="text-xs text-ink/40">Modifié {{ $project->updated_at->diffForHumans() }}</p>
                    </div>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            @empty
                <p class="py-10 text-sm text-ink/50">Aucun brouillon en attente. Tout est publié.</p>
            @endforelse
        </section>
    </div>
</div>
