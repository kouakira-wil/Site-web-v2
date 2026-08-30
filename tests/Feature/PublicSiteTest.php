<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cycle;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_lists_featured_projects(): void
    {
        $featured = Project::factory()->featured()->create(['title' => 'Refonte Aurore']);
        Project::factory()->create(['title' => 'Projet discret']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee($featured->title);
        $response->assertDontSee('Projet discret');
    }

    public function test_project_index_filters_by_category(): void
    {
        $kept = Project::factory()->create(['title' => 'Identité Nord']);
        Project::factory()->create(['title' => 'Autre projet']);

        $response = $this->get(route('projects.index', [
            'categorie' => $kept->category->slug,
        ]));

        $response->assertOk();
        $response->assertSee('Identité Nord');
        $response->assertDontSee('Autre projet');
    }


    public function test_the_filieres_page_groups_filieres_by_cycle(): void
    {
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        // Nom volontairement absent du menu, sinon l'assertion ci-dessous
        // capterait le méga-menu de l'en-tête plutôt que la liste des cycles.
        $vide = Cycle::query()->create(['name' => 'Cycle Sans Filiere', 'short_label' => 'ZZZ', 'position' => 2]);

        $reseaux = Project::factory()->create(['title' => 'Réseaux et Télécommunications']);
        $reseaux->cycles()->attach($bts);

        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertSee('Cycle BTS');
        $response->assertSee('Réseaux et Télécommunications');
        // Un cycle sans filière rattachée n'apparaît pas.
        $response->assertDontSee($vide->name);
    }

    public function test_a_draft_filiere_is_not_listed_under_its_cycle(): void
    {
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        $brouillon = Project::factory()->draft()->create(['title' => 'Filière confidentielle']);
        $brouillon->cycles()->attach($bts);

        $response = $this->get(route('projects.index'));

        $response->assertOk();
        $response->assertDontSee('Filière confidentielle');
    }


    public function test_the_header_lists_filieres_by_cycle_in_a_mega_menu(): void
    {
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        $filiere = Project::factory()->create(['title' => 'Genie Informatique']);
        $filiere->cycles()->attach($bts);

        $response = $this->get(route('home'));

        $response->assertOk();
        // « Filières » est le seul menu déroulant du site.
        $this->assertSame(1, substr_count($response->getContent(), 'class="has-dropdown"'));
        $response->assertSee('Genie Informatique');
    }

    public function test_the_filieres_menu_stays_a_plain_link_without_content(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        // Sans cycle pourvu, pas de panneau vide : aucun déroulant.
        $this->assertSame(0, substr_count($response->getContent(), 'class="has-dropdown"'));
    }

    public function test_publishing_a_filiere_refreshes_the_menu(): void
    {
        $bts = Cycle::query()->create(['name' => 'Cycle BTS', 'short_label' => 'BAC +2', 'position' => 1]);
        $filiere = Project::factory()->create(['title' => 'Filiere Initiale']);
        $filiere->cycles()->attach($bts);

        $this->get(route('home'))->assertSee('Filiere Initiale');

        // Le menu est mis en cache : renommer la filière doit l'invalider.
        $filiere->update(['title' => 'Filiere Renommee']);

        $this->get(route('home'))->assertSee('Filiere Renommee');
    }

    public function test_draft_project_is_not_reachable(): void
    {
        $draft = Project::factory()->draft()->create();

        $this->get(route('projects.show', $draft))->assertNotFound();
    }

    public function test_project_scheduled_in_the_future_is_not_reachable(): void
    {
        $scheduled = Project::factory()->create([
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->addWeek(),
        ]);

        $this->get(route('projects.show', $scheduled))->assertNotFound();
    }

    public function test_draft_post_is_not_reachable(): void
    {
        $draft = Post::factory()->draft()->create();

        $this->get(route('posts.show', $draft))->assertNotFound();
    }

    public function test_published_post_renders_its_markdown_body(): void
    {
        $post = Post::factory()->create([
            'title' => 'Écrire pour le web',
            'body' => "## Sous-titre\n\nUn paragraphe **important**.",
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('<h2>Sous-titre</h2>', false);
        $response->assertSee('<strong>important</strong>', false);
    }

    public function test_raw_html_in_a_post_body_is_stripped(): void
    {
        $post = Post::factory()->create([
            'body' => 'Bonjour <script>alert("xss")</script> tout le monde.',
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertDontSee('<script>alert', false);
    }

    public function test_team_page_hides_inactive_members(): void
    {
        TeamMember::factory()->create(['name' => 'Awa Traoré', 'is_active' => true]);
        TeamMember::factory()->create(['name' => 'Membre Retiré', 'is_active' => false]);

        $response = $this->get(route('team'));

        $response->assertOk();
        $response->assertSee('Awa Traoré');
        $response->assertDontSee('Membre Retiré');
    }
}
