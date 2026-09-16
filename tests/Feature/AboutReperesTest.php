<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\Category;
use App\Models\Cycle;
use App\Models\PageHeader;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AboutReperesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\AboutIntroSeeder::class);
    }

    private function bloc(): PageHeader
    {
        return PageHeader::query()->where('key', 'ecole.reperes')->firstOrFail();
    }

    public function test_the_counts_come_from_the_database(): void
    {
        Cycle::query()->create(['name' => 'Cycle A', 'slug' => 'cycle-a', 'position' => 1]);
        Cycle::query()->create(['name' => 'Cycle B', 'slug' => 'cycle-b', 'position' => 2]);

        $pole = Category::factory()->create();
        Project::factory()->count(3)->create([
            'category_id' => $pole->id,
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        // 2 cycles, 3 filières publiées, 1 pôle.
        $this->assertStringContainsString('reperes-chiffre__valeur">2<', $contenu);
        $this->assertStringContainsString('reperes-chiffre__valeur">3<', $contenu);
        $this->assertStringContainsString('reperes-chiffre__valeur">1<', $contenu);
    }

    public function test_a_draft_filiere_is_not_counted(): void
    {
        $pole = Category::factory()->create();
        Project::factory()->create([
            'category_id' => $pole->id,
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);
        Project::factory()->create([
            'category_id' => $pole->id,
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        // Une seule filière publiée sur les deux.
        $this->assertStringContainsString('reperes-chiffre__valeur">1<', $contenu);
    }

    public function test_a_post_category_is_not_counted_as_a_pole(): void
    {
        // Une rubrique d'actualités partage la table `categories` : elle ne doit
        // pas gonfler le nombre de pôles.
        Category::factory()->create();

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('reperes-chiffre__valeur">0<', $contenu);
    }

    public function test_the_labels_and_button_are_editable(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc()])
            ->assertSee('Libellés des chiffres')
            ->assertSee('Grande photo')
            ->assertSee('Petite photo')
            ->set('tags', "Un libelle\\nUn deuxieme\\nUn troisieme")
            ->set('cta_label', 'Nous rejoindre')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()
            ->assertSee('Un deuxieme')
            ->assertSee('Nous rejoindre');
    }

    public function test_the_strip_follows_the_presentation_section(): void
    {
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertLessThan(
            strpos($contenu, 'reperes-grid'),
            strpos($contenu, 'presentation-grid'),
        );
    }
}
