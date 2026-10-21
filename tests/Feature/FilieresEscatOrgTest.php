<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Import du contenu des fiches filières depuis escat.org.
 *
 * Le seeder est rejouable : il doit compléter les fiches existantes sans les
 * dupliquer ni défaire les rattachements de cycles déjà posés.
 */
class FilieresEscatOrgTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::factory()->create(['name' => 'Pôle Technologie', 'slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);
        Category::factory()->create(['name' => 'Pôle Gestion', 'slug' => 'pole-gestion', 'type' => Category::TYPE_PROJECT]);
        Cycle::query()->create(['name' => 'CAP', 'slug' => 'cap', 'short_label' => 'CAP', 'position' => 0]);
        Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);
        Cycle::query()->create(['name' => 'Cycle Master', 'slug' => 'cycle-master', 'short_label' => 'BAC +5', 'position' => 2]);
    }

    private function importer(): void
    {
        $this->seed(\Database\Seeders\FilieresEscatOrgSeeder::class);
    }

    public function test_every_filiere_arrives_complete(): void
    {
        $this->importer();

        $this->assertSame(21, Project::query()->published()->count());

        foreach (Project::query()->published()->get() as $filiere) {
            foreach (['excerpt', 'description', 'objectives', 'skills', 'outcomes', 'audience'] as $champ) {
                $this->assertNotEmpty($filiere->{$champ}, "{$filiere->slug} : {$champ} est vide.");
            }

            $this->assertCount(3, $filiere->learning_blocks, "{$filiere->slug} : atouts manquants.");
            $this->assertNotNull($filiere->category_id, "{$filiere->slug} : aucun pôle.");
            $this->assertTrue($filiere->cycles->isNotEmpty(), "{$filiere->slug} : aucun cycle.");
        }
    }

    public function test_running_it_twice_changes_nothing(): void
    {
        $this->importer();
        $this->importer();

        // Les fiches sont retrouvées par leur slug : un second passage les met
        // à jour au lieu d'en créer une seconde série.
        $this->assertSame(21, Project::query()->count());
        $this->assertSame(
            21,
            Project::query()->distinct()->count('slug'),
        );
    }

    public function test_it_completes_an_existing_filiere_without_losing_its_cycles(): void
    {
        $existante = Project::factory()->create([
            'title' => 'Génie Informatique et Réseaux',
            'slug' => 'genie-informatique-et-reseaux',
            'objectives' => null,
        ]);
        $existante->cycles()->attach(Cycle::query()->where('slug', 'cycle-master')->firstOrFail());

        $this->importer();

        $apres = $existante->fresh();

        $this->assertNotEmpty($apres->objectives);
        // Le Master était déjà rattaché : la Licence s'ajoute, elle ne remplace pas.
        $this->assertEqualsCanonicalizing(
            ['cycle-master', 'cycle-licence'],
            $apres->cycles->pluck('slug')->all(),
        );
    }

    public function test_repeated_list_entries_are_dropped(): void
    {
        /*
         * La source répète « Business Developer » dans les débouchés de
         * Gestion Commerciale, et un objectif dans deux fiches informatiques.
         * Ce sont des coquilles : les reprendre telles quelles afficherait
         * deux fois la même ligne.
         */
        $this->importer();

        $commerciale = Project::query()->where('slug', 'gestion-commerciale')->firstOrFail();

        $this->assertSame(1, substr_count((string) $commerciale->outcomes, 'Business Developer'));

        foreach (['genie-informatique-et-reseaux', 'informatique-de-gestion'] as $slug) {
            $filiere = Project::query()->where('slug', $slug)->firstOrFail();
            $lignes = preg_split('/\R/', (string) $filiere->objectives, -1, PREG_SPLIT_NO_EMPTY);

            $this->assertSame($lignes, array_values(array_unique($lignes)), "{$slug} : objectif répété.");
        }
    }

    public function test_each_cycle_gets_its_own_filieres(): void
    {
        $this->importer();

        $parCycle = Cycle::query()->get()->mapWithKeys(
            fn (Cycle $cycle): array => [$cycle->slug => $cycle->projects()->count()],
        );

        // Les trois cycles d'escat.org : 5 au CAP, 10 en Licence, 6 en Master.
        $this->assertSame(5, $parCycle['cap']);
        $this->assertSame(10, $parCycle['cycle-licence']);
        $this->assertSame(6, $parCycle['cycle-master']);
    }

    public function test_the_filieres_page_shows_them_all(): void
    {
        $this->importer();

        $contenu = $this->get('/filieres')->assertOk()->getContent();

        foreach (['Intelligence Artificielle', 'Gestion Commerciale', 'Électricité Industrielle'] as $titre) {
            $this->assertStringContainsString($titre, $contenu);
        }
    }
}
