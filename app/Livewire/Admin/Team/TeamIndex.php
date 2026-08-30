<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Team;

use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Équipe')]
class TeamIndex extends Component
{
    public function toggleActive(int $memberId): void
    {
        $member = TeamMember::query()->findOrFail($memberId);
        $member->update(['is_active' => ! $member->is_active]);
    }

    public function delete(int $memberId): void
    {
        TeamMember::query()->findOrFail($memberId)->delete();

        $this->dispatch('notify', message: 'Membre supprimé.');
    }

    public function render(): View
    {
        return view('livewire.admin.team.index', [
            'members' => TeamMember::query()->ordered()->get(),
        ]);
    }
}
