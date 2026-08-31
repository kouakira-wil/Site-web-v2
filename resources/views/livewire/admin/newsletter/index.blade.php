<div>
    <x-admin.heading title="Lettre d’information"
                     :breadcrumb="['Administration' => route('admin.dashboard'), 'Lettre d’information' => null]"
                     :subtitle="$total.' adresse(s) inscrite(s) depuis le site.'" />

    <x-admin.card body-class="p-0">
        @forelse ($subscribers as $subscriber)
            <div wire:key="inscrit-{{ $subscriber->id }}"
                 class="flex flex-wrap items-center justify-between gap-4 border-b border-app-line px-5 py-3.5 last:border-b-0">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium">{{ $subscriber->email }}</p>
                    <p class="text-xs text-app-muted">Inscrite {{ $subscriber->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex shrink-0 items-center gap-3">
                    @unless ($subscriber->isSubscribed())
                        <span class="rounded bg-app-bg px-2 py-1 text-xs font-semibold text-app-muted">Désinscrite</span>
                    @endunless

                    <x-admin.button variant="danger" wire:click="delete({{ $subscriber->id }})"
                                    wire:confirm="Supprimer définitivement cette adresse ?">
                        Supprimer
                    </x-admin.button>
                </div>
            </div>
        @empty
            <p class="px-5 py-12 text-center text-[13px] text-app-muted">Aucune inscription pour le moment.</p>
        @endforelse

        @if ($subscribers->hasPages())
            <div class="border-t border-app-line px-5 py-3">
                {{ $subscribers->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
