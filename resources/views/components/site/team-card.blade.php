@props([
    'member',
    'fallback' => null,
])

{{-- Carte de membre du gabarit, alimentée par la base. --}}
<div class="team-card">
    <div class="team-card-image">
        <div class="anim-zoomin">
            <img src="{{ $member->photoUrl() ?? $fallback }}"
                 alt="Portrait de {{ $member->name }}" class="img-cover" loading="lazy" decoding="async">
        </div>
    </div>
    <a href="{{ route('team.show', $member) }}" class="team-card-icon" aria-label="Voir la fiche de {{ $member->name }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
            <path d="M7.85986 2.43872L1.7123 8.58629L0.702148 7.57614L6.84971 1.42857H1.43131V0H9.28843V7.85714H7.85986V2.43872Z" fill="currentColor" />
        </svg>
    </a>
    <div class="team-card-content">
        <a href="{{ route('team.show', $member) }}" class="team-card-name">
            <h2 class="h6 fz-font-2xl">{{ $member->name }}</h2>
        </a>
        <p class="team-card-position fz-font-sm !m-0 common-white">{{ $member->role }}</p>
    </div>
</div>
