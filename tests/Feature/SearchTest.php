<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cycle;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
    }

    public function test_the_search_page_opens_without_a_term(): void
    {
        $this->get('/recherche')
            ->assertOk()
            ->assertSee('Saisissez un mot-clé');
    }

    public function test_it_finds_a_filiere_by_its_title(): void
    {
        Project::factory()->create(['title' => 'Genie Electromecanique', 'status' => 'published', 'published_at' => now()->subDay()]);

        $this->get('/recherche?q=Electromecanique')
            ->assertOk()
            ->assertSee('Genie Electromecanique');
    }

    public function test_it_finds_a_post_and_a_team_member(): void
    {
        Post::factory()->create(['title' => 'Portes ouvertes de fevrier']);
        TeamMember::factory()->create(['name' => 'Responsable des admissions', 'is_active' => true]);

        $reponse = $this->get('/recherche?q=admissions');
        $reponse->assertOk();
        $reponse->assertSee('Responsable des admissions');

        $this->get('/recherche?q=Portes')->assertOk()->assertSee('Portes ouvertes de fevrier');
    }

    public function test_a_filiere_is_found_through_its_cycle(): void
    {
        $cycle = Cycle::query()->create(['name' => 'Cycle BTS', 'slug' => 'cycle-bts', 'position' => 1]);
        $filiere = Project::factory()->create([
            'title' => 'Comptabilite appliquee',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $filiere->cycles()->sync([$cycle->id]);

        // « BTS » ne figure dans aucun champ de la filière : seul le cycle le porte.
        $this->get('/recherche?q=BTS')
            ->assertOk()
            ->assertSee('Comptabilite appliquee');
    }

    public function test_sql_wildcards_are_escaped_and_match_nothing(): void
    {
        Project::factory()->create(['title' => 'Une filiere ordinaire', 'status' => 'published', 'published_at' => now()->subDay()]);

        // Sans échappement, « % » remonterait tout le catalogue.
        foreach (['%', '__', '%%'] as $joker) {
            $this->get('/recherche?q='.urlencode($joker))
                ->assertOk()
                ->assertDontSee('Une filiere ordinaire');
        }
    }

    public function test_a_single_character_term_is_refused(): void
    {
        $this->get('/recherche?q=a')
            ->assertOk()
            ->assertSee('au moins 2 caractères', false);
    }

    public function test_an_unknown_term_reports_no_result(): void
    {
        $this->get('/recherche?q=xyznonexistant')
            ->assertOk()
            ->assertSee('Aucun résultat', false);
    }

    public function test_the_search_form_points_at_the_search_route(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('action="'.route('search').'"', false)
            ->assertSee('name="q"', false);
    }
}
