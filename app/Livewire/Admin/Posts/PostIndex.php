<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Articles')]
class PostIndex extends Component
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

    public function delete(int $postId): void
    {
        Post::query()->findOrFail($postId)->delete();

        $this->dispatch('notify', message: 'Actualité supprimée.');
    }

    public function render(): View
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($this->search !== '', fn (Builder $query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->latest('updated_at')
            ->paginate(12);

        return view('livewire.admin.posts.index', [
            'posts' => $posts,
        ]);
    }
}
