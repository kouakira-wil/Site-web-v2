<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Projects;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Projets')]
class ProjectIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'statut', except: '')]
    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function toggleFeatured(int $projectId): void
    {
        $project = Project::query()->findOrFail($projectId);
        $project->update(['is_featured' => ! $project->is_featured]);

        $this->dispatch('notify', message: 'Mise en avant mise à jour.');
    }

    public function delete(int $projectId): void
    {
        Project::query()->findOrFail($projectId)->delete();

        $this->dispatch('notify', message: 'Filière supprimée.');
    }

    public function render(): View
    {
        $projects = Project::query()
            ->with('category')
            ->when($this->search !== '', fn (Builder $query) => $query->where(
                fn (Builder $q) => $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('client', 'like', '%'.$this->search.'%')
            ))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('updated_at')
            ->paginate(12);

        return view('livewire.admin.projects.index', [
            'projects' => $projects,
        ]);
    }
}
