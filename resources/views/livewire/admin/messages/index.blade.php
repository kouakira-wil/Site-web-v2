<div>
    <x-admin.heading title="Messages" subtitle="Les demandes envoyées depuis le formulaire de contact du site.">
        <x-slot:actions>
            <label for="filtre-messages" class="sr-only">Filtrer par statut</label>
            <select id="filtre-messages" wire:model.live="status"
                    class="rounded-full border-ink/20 bg-paper px-5 py-3 text-sm focus:border-accent focus:ring-0">
                <option value="">Tous</option>
                <option value="new">Nouveaux</option>
                <option value="read">Lus</option>
                <option value="replied">Répondus</option>
                <option value="archived">Archivés</option>
            </select>
        </x-slot:actions>
    </x-admin.heading>

    @if ($messages->isEmpty())
        <div class="rounded-card border border-dashed border-ink/20 px-6 py-20 text-center text-ink/55">
            Aucun message dans cette vue.
        </div>
    @else
        <ul class="divide-y divide-ink/10 overflow-hidden rounded-card border border-ink/10">
            @foreach ($messages as $message)
                <li wire:key="message-{{ $message->id }}" @class(['bg-accent/[0.04]' => $message->status === 'new'])>
                    <button type="button" wire:click="open({{ $message->id }})"
                            class="flex w-full items-start justify-between gap-6 px-6 py-5 text-left hover:bg-paper-warm/60"
                            aria-expanded="{{ $openId === $message->id ? 'true' : 'false' }}">
                        <div class="min-w-0">
                            <p class="font-medium">
                                {{ $message->name }}
                                <span class="font-normal text-ink/45">&lt;{{ $message->email }}&gt;</span>
                            </p>
                            <p class="mt-1 truncate text-sm text-ink/65">{{ $message->subject ?: 'Sans sujet' }}</p>
                        </div>

                        <div class="flex shrink-0 items-center gap-4">
                            <x-admin.status-pill :status="$message->status" />
                            <time class="hidden text-xs text-ink/40 sm:block" datetime="{{ $message->created_at->toIso8601String() }}">
                                {{ $message->created_at->translatedFormat('d M Y') }}
                            </time>
                        </div>
                    </button>

                    @if ($openId === $message->id)
                        <div class="border-t border-ink/10 bg-paper-warm/40 px-6 py-6">
                            <dl class="grid gap-4 text-sm sm:grid-cols-3">
                                <div>
                                    <dt class="text-ink/45">Reçu le</dt>
                                    <dd class="mt-1">{{ $message->created_at->translatedFormat('d F Y à H:i') }}</dd>
                                </div>
                                @if ($message->phone)
                                    <div>
                                        <dt class="text-ink/45">Téléphone</dt>
                                        <dd class="mt-1">{{ $message->phone }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-ink/45">Adresse IP</dt>
                                    <dd class="mt-1 font-mono text-xs">{{ $message->ip_address ?? '—' }}</dd>
                                </div>
                            </dl>

                            <p class="mt-6 whitespace-pre-line leading-relaxed text-ink/80">{{ $message->message }}</p>

                            <div class="mt-8 flex flex-wrap items-center gap-3">
                                <x-admin.button variant="primary"
                                                :href="'mailto:'.$message->email.'?subject='.rawurlencode('Re : '.($message->subject ?: 'votre message'))">
                                    Répondre par e-mail
                                </x-admin.button>

                                @if ($message->status !== 'replied')
                                    <x-admin.button variant="secondary" wire:click="setStatus({{ $message->id }}, 'replied')">
                                        Marquer comme répondu
                                    </x-admin.button>
                                @endif

                                @if ($message->status !== 'archived')
                                    <x-admin.button variant="secondary" wire:click="setStatus({{ $message->id }}, 'archived')">
                                        Archiver
                                    </x-admin.button>
                                @endif

                                <x-admin.button variant="danger" wire:click="delete({{ $message->id }})"
                                                wire:confirm="Supprimer définitivement ce message ?">
                                    Supprimer
                                </x-admin.button>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="mt-8">
            {{ $messages->links() }}
        </div>
    @endif
</div>
