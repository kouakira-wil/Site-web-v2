<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\AdminSearch;
use App\Livewire\Admin\Dashboard;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    public function test_a_draft_is_findable_from_the_back_office(): void
    {
        // Un brouillon est invisible du public : le retrouver est justement
        // l'usage principal de la recherche interne.
        Project::factory()->create([
            'title' => 'Filiere encore en brouillon',
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);

        Livewire::test(AdminSearch::class)
            ->set('q', 'brouillon')
            ->assertSee('Filiere encore en brouillon');
    }

    public function test_sql_wildcards_are_escaped(): void
    {
        Post::factory()->create(['title' => 'Une actualite ordinaire']);

        Livewire::test(AdminSearch::class)
            ->set('q', '%')
            ->assertDontSee('Une actualite ordinaire');
    }

    public function test_a_single_character_term_is_refused(): void
    {
        Livewire::test(AdminSearch::class)
            ->set('q', 'a')
            ->assertSee('au moins 2 caractères');
    }

    public function test_the_dashboard_renders_its_panels(): void
    {
        Post::factory()->create(['title' => 'Parution programmee', 'published_at' => now()->addWeek()]);

        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('Filières publiées')
            ->assertSee('Publications sur 12 mois')
            ->assertSee('Calendrier des parutions')
            ->assertSee('Parution programmee');
    }

    public function test_the_dashboard_survives_an_empty_database(): void
    {
        // Aucun contenu : les moyennes et variations ne doivent pas diviser par zéro.
        Livewire::test(Dashboard::class)
            ->assertOk()
            ->assertSee('Aucune parution programmée');
    }
}
