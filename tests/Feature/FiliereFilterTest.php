<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le tri par pôle de la page Filières se joue dans le navigateur : toutes
 * les fiches sont dans la page, le clic ne déclenche aucun rechargement.
 */
class FiliereFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_filter_sorts_without_reloading_the_page(): void
    {
        Category::factory()->create(['name' => 'Pole Technologie', 'slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);

        $contenu = $this->get('/filieres')->assertOk()->getContent();

        /*
         * `initPortfolioFilter()` du gabarit s'accroche à
         * `.filter-portfolio .filter-btn` et masque les fiches dont le
         * `data-category` ne correspond pas au `data-filter` cliqué.
         */
        $this->assertStringContainsString('filter-portfolio', $contenu);
        $this->assertStringContainsString('data-filter="all"', $contenu);
        $this->assertStringContainsString('data-filter="pole-technologie"', $contenu);

        // Plus aucun aller-retour au serveur pour filtrer.
        $this->assertStringNotContainsString('categorie=pole-technologie', $contenu);
    }

    public function test_every_card_carries_its_pole(): void
    {
        $techno = Category::factory()->create(['slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);
        $gestion = Category::factory()->create(['slug' => 'pole-gestion', 'type' => Category::TYPE_PROJECT]);

        Project::factory()->count(2)->create([
            'category_id' => $techno->id,
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);
        Project::factory()->create([
            'category_id' => $gestion->id,
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);

        $contenu = $this->get('/filieres')->assertOk()->getContent();

        // Le filtre client ne révèle que ce qui est déjà dans la page.
        $this->assertSame(3, substr_count($contenu, 'mg-portfolio-item'));
        $this->assertSame(2, substr_count($contenu, 'data-category="pole-technologie"'));
        $this->assertSame(1, substr_count($contenu, 'data-category="pole-gestion"'));
    }

    public function test_a_draft_stays_out_of_the_grid(): void
    {
        $pole = Category::factory()->create(['slug' => 'pole-gestion', 'type' => Category::TYPE_PROJECT]);

        Project::factory()->create([
            'title' => 'Filiere brouillon',
            'category_id' => $pole->id,
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $contenu = $this->get('/filieres')->assertOk()->getContent();

        $this->assertStringNotContainsString('Filiere brouillon', $contenu);
    }
}
