<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Projects;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Contracts\View\View;
use App\View\Components\Site\FilieresMenu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class ProjectForm extends Component
{
    use WithFileUploads;

    public ?Project $project = null;

    public ?int $category_id = null;

    public string $title = '';

    public string $slug = '';

    public string $client = '';

    public ?string $year = null;

    public string $external_url = '';

    public string $excerpt = '';

    public string $description = '';

    public string $status = Project::STATUS_DRAFT;

    public bool $is_featured = false;

    public int $position = 0;

    public ?string $published_at = null;

    /** @var array<int, int> */
    public array $selectedCycles = [];

    public ?TemporaryUploadedFile $cover = null;

    /** @var array<int, TemporaryUploadedFile> */
    public array $gallery = [];

    public function mount(?Project $project = null): void
    {
        if ($project?->exists !== true) {
            $this->published_at = now()->format('Y-m-d\TH:i');

            return;
        }

        $this->project = $project;
        $this->fill($project->only([
            'category_id', 'title', 'slug', 'excerpt', 'description', 'status', 'position',
        ]));

        $this->client = (string) $project->client;
        $this->year = $project->year ? (string) $project->year : null;
        $this->external_url = (string) $project->external_url;
        $this->is_featured = $project->is_featured;
        $this->published_at = $project->published_at?->format('Y-m-d\TH:i');
        $this->selectedCycles = $project->cycles->pluck('id')->all();
    }

    /**
     * @return array<string, array<int, mixed>|string>
     */
    protected function rules(): array
    {
        $ignore = $this->project?->getKey();

        return [
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', 'alpha_dash', 'unique:projects,slug'.($ignore ? ','.$ignore : '')],
            'category_id' => ['nullable', 'exists:categories,id'],
            'client' => ['nullable', 'string', 'max:150'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'external_url' => ['nullable', 'url', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'description' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', 'in:draft,published'],
            'is_featured' => ['boolean'],
            'position' => ['integer', 'min:0', 'max:9999'],
            'published_at' => ['nullable', 'date'],
            'selectedCycles' => ['array'],
            'selectedCycles.*' => ['exists:cycles,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        return [
            'title' => 'titre',
            'slug' => 'identifiant d’URL',
            'category_id' => 'catégorie',
            'excerpt' => 'accroche',
            'description' => 'description',
            'cover' => 'image de couverture',
            'published_at' => 'date de publication',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        $project = $this->project ?? new Project();

        $project->fill([
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'client' => $data['client'],
            'year' => $data['year'],
            'external_url' => $data['external_url'],
            'excerpt' => $data['excerpt'],
            'description' => $data['description'],
            'status' => $data['status'],
            'is_featured' => $data['is_featured'],
            'position' => $data['position'],
            'published_at' => $data['published_at'],
        ]);

        // Only overwrite the stored slug when the editor supplied one.
        if (filled($data['slug'])) {
            $project->slug = $data['slug'];
        }

        if ($this->cover !== null) {
            $this->replaceCover($project);
        }

        $project->save();
        $project->cycles()->sync($data['selectedCycles']);

        // sync() ne déclenche pas d'événement de modèle : on vide le cache du
        // méga-menu explicitement, sinon un changement de cycle n'y paraîtrait pas.
        Cache::forget(FilieresMenu::CACHE_KEY);

        $this->storeGalleryUploads($project);

        $this->project = $project->refresh();
        $this->slug = $project->slug;
        $this->reset('cover', 'gallery');

        session()->flash('status', 'Projet enregistré.');

        $this->redirectRoute('admin.projects.edit', $project, navigate: true);
    }

    public function removeImage(int $imageId): void
    {
        $image = $this->project?->images()->whereKey($imageId)->firstOrFail();

        if ($image === null) {
            return;
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        $this->project->refresh();
    }

    public function removeCover(): void
    {
        if ($this->project?->cover_image === null) {
            return;
        }

        Storage::disk('public')->delete($this->project->cover_image);
        $this->project->update(['cover_image' => null]);
        $this->project->refresh();
    }

    private function replaceCover(Project $project): void
    {
        if (filled($project->cover_image)) {
            Storage::disk('public')->delete($project->cover_image);
        }

        $project->cover_image = $this->cover->store('projets/couvertures', 'public');
    }

    private function storeGalleryUploads(Project $project): void
    {
        if ($this->gallery === []) {
            return;
        }

        $nextPosition = (int) $project->images()->max('position') + 1;

        foreach ($this->gallery as $file) {
            ProjectImage::query()->create([
                'project_id' => $project->id,
                'path' => $file->store('projets/galerie', 'public'),
                'alt' => $project->title,
                'position' => $nextPosition++,
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.projects.form', [
            'categories' => Category::query()->ofType(Category::TYPE_PROJECT)->ordered()->get(),
            'cycles' => Cycle::query()->ordered()->get(),
            'pageTitle' => $this->project?->exists ? 'Modifier le projet' : 'Nouveau projet',
        ]);
    }
}
