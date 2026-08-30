<footer class="mt-section bg-ink text-paper">
    <div class="shell py-section">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <p class="text-sm uppercase tracking-[0.2em] text-paper/50">Un projet en tête ?</p>
                <a href="{{ route('contact') }}" class="mt-6 block font-display text-display-lg text-balance hover:text-accent-soft">
                    Écrivons la suite ensemble.
                </a>
            </div>

            <div class="grid gap-10 sm:grid-cols-2 lg:col-span-5">
                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-paper/50">Navigation</h2>
                    <ul class="mt-5 space-y-3 text-paper/80">
                        <li><a href="{{ route('projects.index') }}" class="link-underline">Réalisations</a></li>
                        <li><a href="{{ route('posts.index') }}" class="link-underline">Journal</a></li>
                        <li><a href="{{ route('team') }}" class="link-underline">Équipe</a></li>
                        <li><a href="{{ route('contact') }}" class="link-underline">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-sm uppercase tracking-[0.2em] text-paper/50">Contact</h2>
                    <ul class="mt-5 space-y-3 text-paper/80">
                        <li><a href="mailto:{{ config('mail.contact_recipient') ?: 'contact@example.com' }}" class="link-underline">{{ config('mail.contact_recipient') ?: 'contact@example.com' }}</a></li>
                        <li>Abidjan, Côte d’Ivoire</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-16 flex flex-col gap-4 border-t border-paper/15 pt-8 text-sm text-paper/50 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ now()->year }} {{ config('app.name') }}. Tous droits réservés.</p>
            <a href="{{ route('login') }}" class="link-underline">Espace équipe</a>
        </div>
    </div>
</footer>
