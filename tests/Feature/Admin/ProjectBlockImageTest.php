<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Projects\ProjectForm;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectBlockImageTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsStaff(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    public function test_a_block_image_is_stored_and_kept_with_its_block(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Filiere illustree')
            ->set('learningBlocks', [
                ['title' => 'Axe un', 'description' => '', 'points' => '', 'image' => null],
            ])
            ->set('blockImages.0', UploadedFile::fake()->image('axe.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $blocs = Project::query()->where('title', 'Filiere illustree')->firstOrFail()->learning_blocks;

        $this->assertNotNull($blocs[0]['image']);
        Storage::disk('public')->assertExists($blocs[0]['image']);
    }

    public function test_the_public_page_falls_back_to_the_cover_without_a_block_image(): void
    {
        $project = Project::factory()->create([
            'cover_image' => 'projets/couverture.webp',
            'learning_blocks' => [
                ['title' => 'Axe sans visuel', 'description' => '', 'points' => [], 'image' => null],
            ],
        ]);

        $bloc = $project->learningBlocks()[0];

        $this->assertSame($project->coverUrl(), $bloc['image']);
    }

    public function test_a_path_not_belonging_to_the_project_is_discarded(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        // Fichier d'un tiers, que le formulaire ne doit jamais adopter.
        Storage::disk('public')->put('projets/blocs/fichier-d-autrui.webp', 'contenu');

        $project = Project::factory()->create(['learning_blocks' => []]);

        Livewire::test(ProjectForm::class, ['project' => $project])
            ->set('learningBlocks', [
                ['title' => 'Axe pirate', 'description' => '', 'points' => '', 'image' => 'projets/blocs/fichier-d-autrui.webp'],
            ])
            ->call('save')
            ->assertHasNoErrors();

        $blocs = $project->refresh()->learning_blocks;

        $this->assertNull($blocs[0]['image']);
        // Le fichier du tiers est intact.
        Storage::disk('public')->assertExists('projets/blocs/fichier-d-autrui.webp');
    }

    public function test_removing_a_block_deletes_its_image_from_disk(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Storage::disk('public')->put('projets/blocs/a-supprimer.webp', 'contenu');
        $project = Project::factory()->create([
            'learning_blocks' => [
                ['title' => 'Axe un', 'description' => '', 'points' => [], 'image' => 'projets/blocs/a-supprimer.webp'],
            ],
        ]);

        Livewire::test(ProjectForm::class, ['project' => $project])
            ->call('removeLearningBlock', 0)
            ->call('save')
            ->assertHasNoErrors();

        Storage::disk('public')->assertMissing('projets/blocs/a-supprimer.webp');
    }
}
