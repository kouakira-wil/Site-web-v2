<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Vue d’ensemble')]
class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.admin.dashboard', [
            'stats' => [
                [
                    'label' => 'Filières publiées',
                    'value' => Project::query()->published()->count(),
                    'detail' => Project::query()->where('status', Project::STATUS_DRAFT)->count().' brouillon(s)',
                    'route' => route('admin.projects.index'),
                ],
                [
                    'label' => 'Actualités publiées',
                    'value' => Post::query()->published()->count(),
                    'detail' => Post::query()->where('status', Post::STATUS_DRAFT)->count().' brouillon(s)',
                    'route' => route('admin.posts.index'),
                ],
                [
                    'label' => 'Membres actifs',
                    'value' => TeamMember::query()->active()->count(),
                    'detail' => TeamMember::query()->where('is_active', false)->count().' masqué(s)',
                    'route' => route('admin.team.index'),
                ],
                [
                    'label' => 'Messages non lus',
                    'value' => ContactMessage::query()->unread()->count(),
                    'detail' => ContactMessage::query()->count().' au total',
                    'route' => route('admin.messages.index'),
                ],
            ],
            'latestMessages' => ContactMessage::query()->latest()->take(5)->get(),
            'draftProjects' => Project::query()
                ->where('status', Project::STATUS_DRAFT)
                ->latest('updated_at')
                ->take(5)
                ->get(),
        ]);
    }
}
