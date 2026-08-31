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

    public string $objectives = '';

    public string $skills = '';

    public string $outcomes = '';

    public string $audience = '';

    /**
     * Blocs « Ce que vous y apprenez ».
     *
     * Les puces sont manipulées comme un texte (une par ligne) dans le
     * formulaire, et converties en tableau au moment de l'enregistrement.
     *
     * `image` conserve le chemin déjà stocké ; les nouveaux fichiers arrivent
     * séparément dans $blockImages, indexés sur la même position.
     *
     * @var array<int, array{title: string, description: string, points: string, image: string|null}>
     */
    public array $learningBlocks = [];

    /**
     * Nouveaux visuels de blocs, indexés par position de bloc.
     *
     * @var array<int, TemporaryUploadedFile>
     */
    public array $blockImages = [];

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

        $this->objectives = (string) $project->objectives;
        $this->skills = (string) $project->skills;
        $this->outcomes = (string) $project->outcomes;
        $this->audience = (string) $project->audience;

        $this->learningBlocks = collect($project->learning_blocks ?? [])
            ->map(fn (array $bloc): array => [
                'title' => (string) ($bloc['title'] ?? ''),
                'description' => (string) ($bloc['description'] ?? ''),
                'points' => implode(PHP_EOL, $bloc['points'] ?? []),
                'image' => $bloc['image'] ?? null,
            ])
            ->all();
    }

    public function addLearningBlock(): void
    {
        $this->learningBlocks[] = ['title' => '', 'description' => '', 'points' => '', 'image' => null];
    }

    public function removeLearningBlock(int $index): void
    {
        unset($this->learningBlocks[$index], $this->blockImages[$index]);

        // Réindexer les deux tableaux ensemble : ils sont appariés par position.
        $this->learningBlocks = array_values($this->learningBlocks);
        $this->blockImages = array_values($this->blockImages);
    }

    /**
     * Retire le visuel d'un bloc sans toucher au reste de sa saisie.
     */
    public function removeBlockImage(int $index): void
    {
        unset($this->blockImages[$index]);

        if (filled($this->learningBlocks[$index]['image'] ?? null)) {
            $this->learningBlocks[$index]['image'] = null;
        }
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
            'objectives' => ['nullable', 'string', 'max:4000'],
            'skills' => ['nullable', 'string', 'max:4000'],
            'outcomes' => ['nullable', 'string', 'max:4000'],
            'audience' => ['nullable', 'string', 'max:2000'],
            'learningBlocks' => ['array', 'max:12'],
            'learningBlocks.*.title' => ['required', 'string', 'max:180'],
            'learningBlocks.*.description' => ['nullable', 'string', 'max:1000'],
            'learningBlocks.*.points' => ['nullable', 'string', 'max:1000'],
            'learningBlocks.*.image' => ['nullable', 'string', 'max:255'],
            'blockImages' => ['array', 'max:12'],
            'blockImages.*' => ['image', 'mimes:jpg,jpeg,png,webp,avif', 'max:4096'],
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
            'objectives' => 'objectifs de la formation',
            'skills' => 'compétences clés',
            'outcomes' => 'débouchés professionnels',
            'audience' => 'public visé',
            'learningBlocks.*.title' => 'titre du bloc',
        ];
    }

    public function save(): void
    {
        $data = $this->validate();

        // Photographie des visuels de blocs avant modification : ce qui n'est
        // plus référencé après l'enregistrement sera supprimé du disque.
        $cheminsAvant = $this->storedBlockImagePaths();

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
            'objectives' => $data['objectives'],
            'skills' => $data['skills'],
            'outcomes' => $data['outcomes'],
            'audience' => $data['audience'],
            'learning_blocks' => $this->normalisedLearningBlocks(
                $this->resolveBlockImages($data['learningBlocks'])
            ),
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
        $this->purgeOrphanBlockImages($cheminsAvant, $project);

        $this->reset('cover', 'gallery', 'blockImages');

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

    /**
     * Turns the form's newline-separated bullet points back into arrays.
     *
     * @param  array<int, array{title: string, description: string, points: string}>  $blocs
     * @return array<int, array{title: string, description: string, points: array<int, string>}>
     */
    private function normalisedLearningBlocks(array $blocs): array
    {
        return collect($blocs)
            ->filter(fn (array $bloc): bool => filled($bloc['title'] ?? null))
            ->map(fn (array $bloc): array => [
                'title' => trim($bloc['title']),
                'description' => trim((string) ($bloc['description'] ?? '')),
                'points' => collect(preg_split('/\R/', (string) ($bloc['points'] ?? '')))
                    ->map(fn (string $point): string => trim($point))
                    ->filter()
                    ->values()
                    ->all(),
                'image' => $bloc['image'] ?? null,
            ])
            ->values()
            ->all();
    }

    /**
     * Dossier des visuels de blocs. Sert aussi de garde-fou : on ne supprime
     * jamais un fichier situé hors de ce dossier.
     */
    private const BLOCK_IMAGE_DIR = 'projets/blocs';

    /**
     * Chemins des visuels actuellement enregistrés pour ce projet.
     *
     * @return array<int, string>
     */
    private function storedBlockImagePaths(): array
    {
        return collect($this->project?->learning_blocks ?? [])
            ->pluck('image')
            ->filter()
            ->map(fn ($chemin): string => (string) $chemin)
            ->values()
            ->all();
    }

    /**
     * Résout le visuel de chaque bloc : nouveau fichier téléversé, chemin déjà
     * en base, ou rien.
     *
     * Le chemin transmis par le formulaire n'est jamais pris au mot : seul un
     * chemin déjà rattaché à ce projet est conservé. Sans ce filtre, un compte
     * rédacteur pourrait faire pointer un bloc vers un fichier arbitraire du
     * disque public, puis en provoquer la suppression au tour suivant.
     *
     * @param  array<int, array<string, mixed>>  $blocs
     * @return array<int, array<string, mixed>>
     */
    private function resolveBlockImages(array $blocs): array
    {
        $autorises = $this->storedBlockImagePaths();

        foreach ($blocs as $index => $bloc) {
            $televerse = $this->blockImages[$index] ?? null;

            if ($televerse instanceof TemporaryUploadedFile) {
                $blocs[$index]['image'] = $televerse->store(self::BLOCK_IMAGE_DIR, 'public');

                continue;
            }

            $chemin = (string) ($bloc['image'] ?? '');
            $blocs[$index]['image'] = in_array($chemin, $autorises, true) ? $chemin : null;
        }

        return $blocs;
    }

    /**
     * Supprime du disque les visuels qu'aucun bloc ne référence plus, qu'ils
     * aient été remplacés, retirés, ou emportés par la suppression du bloc.
     *
     * @param  array<int, string>  $cheminsAvant
     */
    private function purgeOrphanBlockImages(array $cheminsAvant, Project $project): void
    {
        $conserves = collect($project->learning_blocks ?? [])->pluck('image')->filter()->all();

        foreach (array_diff($cheminsAvant, $conserves) as $chemin) {
            // Ceinture et bretelles : jamais de suppression hors du dossier dédié.
            if (str_starts_with($chemin, self::BLOCK_IMAGE_DIR.'/')) {
                Storage::disk('public')->delete($chemin);
            }
        }
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
