<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Projects\ProjectForm;
use App\Livewire\Admin\Projects\ProjectIndex;
use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsStaff(): User
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($user);

        return $user;
    }

    public function test_a_project_can_be_created_with_a_generated_slug(): void
    {
        $this->actingAsStaff();
        $category = Category::factory()->create();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Refonte de la marque Aurore')
            ->set('category_id', $category->id)
            ->set('status', Project::STATUS_PUBLISHED)
            ->set('published_at', now()->format('Y-m-d\TH:i'))
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'Refonte de la marque Aurore',
            'slug' => 'refonte-de-la-marque-aurore',
            'status' => Project::STATUS_PUBLISHED,
        ]);
    }

    public function test_a_project_requires_a_title(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title' => 'required']);
    }

    public function test_a_duplicate_slug_is_rejected(): void
    {
        $this->actingAsStaff();
        Project::factory()->create(['slug' => 'projet-existant']);

        Livewire::test(ProjectForm::class)
            ->set('title', 'Nouveau projet')
            ->set('slug', 'projet-existant')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique']);
    }

    public function test_editing_a_project_keeps_its_own_slug_valid(): void
    {
        $this->actingAsStaff();
        $project = Project::factory()->create(['slug' => 'mon-projet']);

        Livewire::test(ProjectForm::class, ['project' => $project])
            ->set('title', 'Titre remanié')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Titre remanié', $project->refresh()->title);
        $this->assertSame('mon-projet', $project->slug);
    }

    public function test_a_cover_image_is_stored_on_the_public_disk(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Projet avec visuel')
            ->set('cover', UploadedFile::fake()->image('couverture.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $project = Project::query()->where('title', 'Projet avec visuel')->firstOrFail();

        $this->assertNotNull($project->cover_image);
        Storage::disk('public')->assertExists($project->cover_image);
    }

    public function test_a_non_image_upload_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Projet douteux')
            ->set('cover', UploadedFile::fake()->create('script.php', 20))
            ->call('save')
            ->assertHasErrors('cover');
    }


    public function test_a_project_can_be_attached_to_study_cycles(): void
    {
        $this->actingAsStaff();
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'short_label' => 'BAC +3', 'position' => 2]);

        Livewire::test(ProjectForm::class)
            ->set('title', 'Réseaux et Télécommunications')
            ->set('selectedCycles', [$bts->id, $licence->id])
            ->call('save')
            ->assertHasNoErrors();

        $project = Project::query()->where('title', 'Réseaux et Télécommunications')->firstOrFail();

        $this->assertEqualsCanonicalizing(
            [$bts->id, $licence->id],
            $project->cycles->pluck('id')->all(),
        );
    }

    public function test_editing_a_project_replaces_its_cycles(): void
    {
        $this->actingAsStaff();
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        $master = Cycle::query()->create(['name' => 'Cycle Master', 'short_label' => 'BAC +5', 'position' => 3]);

        $project = Project::factory()->create();
        $project->cycles()->attach($bts);

        Livewire::test(ProjectForm::class, ['project' => $project])
            ->set('selectedCycles', [$master->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame([$master->id], $project->refresh()->cycles->pluck('id')->all());
    }

    public function test_an_unknown_cycle_is_rejected(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Filière douteuse')
            ->set('selectedCycles', [9999])
            ->call('save')
            ->assertHasErrors('selectedCycles.0');
    }


    public function test_programme_fields_are_saved(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Filiere Programme')
            ->set('objectives', "Objectif un
Objectif deux")
            ->set('skills', "Competence un")
            ->set('outcomes', "Debouche un
Debouche deux")
            ->set('audience', "Titulaires du BAC")
            ->call('save')
            ->assertHasNoErrors();

        $project = Project::query()->where('title', 'Filiere Programme')->firstOrFail();

        $this->assertSame(['Objectif un', 'Objectif deux'], $project->lines('objectives'));
        $this->assertSame(['Competence un'], $project->lines('skills'));
        $this->assertSame(['Debouche un', 'Debouche deux'], $project->lines('outcomes'));
        $this->assertSame(['Titulaires du BAC'], $project->lines('audience'));
    }

    public function test_learning_blocks_are_saved_with_their_bullet_points(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Filiere Blocs')
            ->call('addLearningBlock')
            ->set('learningBlocks.0.title', 'Ateliers pratiques')
            ->set('learningBlocks.0.description', 'Sur equipements reels.')
            ->set('learningBlocks.0.points', "Equipements reels

Projets notes")
            ->call('save')
            ->assertHasNoErrors();

        $blocs = Project::query()->where('title', 'Filiere Blocs')->firstOrFail()->learningBlocks();

        $this->assertCount(1, $blocs);
        $this->assertSame('Ateliers pratiques', $blocs[0]['title']);
        // La ligne vide est ecartee.
        $this->assertSame(['Equipements reels', 'Projets notes'], $blocs[0]['points']);
    }

    public function test_a_learning_block_requires_a_title(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->set('title', 'Filiere Incomplete')
            ->call('addLearningBlock')
            ->set('learningBlocks.0.description', 'Sans titre.')
            ->call('save')
            ->assertHasErrors('learningBlocks.0.title');
    }

    public function test_a_learning_block_can_be_removed(): void
    {
        $this->actingAsStaff();

        Livewire::test(ProjectForm::class)
            ->call('addLearningBlock')
            ->call('addLearningBlock')
            ->assertCount('learningBlocks', 2)
            ->call('removeLearningBlock', 0)
            ->assertCount('learningBlocks', 1);
    }

    public function test_the_index_filters_by_search_term(): void
    {
        $this->actingAsStaff();
        Project::factory()->create(['title' => 'Identité Aurore']);
        Project::factory()->create(['title' => 'Application Zenith']);

        Livewire::test(ProjectIndex::class)
            ->set('search', 'Aurore')
            ->assertSee('Identité Aurore')
            ->assertDontSee('Application Zenith');
    }

    public function test_featured_state_can_be_toggled_from_the_index(): void
    {
        $this->actingAsStaff();
        $project = Project::factory()->create(['is_featured' => false]);

        Livewire::test(ProjectIndex::class)
            ->call('toggleFeatured', $project->id);

        $this->assertTrue($project->refresh()->is_featured);
    }

    public function test_a_project_can_be_soft_deleted(): void
    {
        $this->actingAsStaff();
        $project = Project::factory()->create();

        Livewire::test(ProjectIndex::class)
            ->call('delete', $project->id);

        $this->assertSoftDeleted($project);
    }
}
