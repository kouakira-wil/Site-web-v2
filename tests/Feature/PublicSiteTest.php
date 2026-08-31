<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cycle;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\User;
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


    public function test_the_filiere_page_shows_its_programme(): void
    {
        $filiere = Project::factory()->create([
            'title' => 'Filiere Detaillee',
            'objectives' => "Diagnostiquer une panne
Realiser la maintenance",
            'skills' => "Mecanique industrielle",
            'outcomes' => "Technicien de maintenance",
            'audience' => "Titulaires du BEPC",
            'learning_blocks' => [
                ['title' => 'Ateliers pratiques', 'description' => 'Sur equipements reels.', 'points' => ['Projets notes']],
            ],
        ]);

        $response = $this->get(route('projects.show', $filiere));

        $response->assertOk();
        $response->assertSee('Objectifs de la formation');
        $response->assertSee('Diagnostiquer une panne');
        $response->assertSee('Compétences clés');
        $response->assertSee('Mecanique industrielle');
        $response->assertSee('Technicien de maintenance');
        $response->assertSee('Titulaires du BEPC');
        $response->assertSee('Ce que vous y apprenez');
        $response->assertSee('Ateliers pratiques');
        $response->assertSee('Projets notes');
    }

    public function test_empty_programme_sections_are_not_rendered(): void
    {
        $filiere = Project::factory()->create([
            'title' => 'Filiere Minimale',
            'objectives' => null,
            'skills' => null,
            'outcomes' => null,
            'audience' => null,
            'learning_blocks' => null,
        ]);

        $response = $this->get(route('projects.show', $filiere));

        $response->assertOk();
        $response->assertDontSee('Le programme en détail');
        $response->assertDontSee('Ce que vous y apprenez');
    }


    public function test_the_post_page_shows_its_gallery_under_the_main_image(): void
    {
        $post = Post::factory()->create(['title' => 'Actualite avec galerie']);
        PostImage::query()->create(['post_id' => $post->id, 'path' => 'a.webp', 'caption' => 'Premiere vue', 'position' => 1]);
        PostImage::query()->create(['post_id' => $post->id, 'path' => 'b.webp', 'caption' => 'Deuxieme vue', 'position' => 2]);
        PostImage::query()->create(['post_id' => $post->id, 'path' => 'c.webp', 'caption' => 'Vue isolee', 'position' => 3]);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee('post-gallery');

        // Chaque image legendee rend sa propre legende.
        $response->assertSee('Premiere vue');
        $response->assertSee('Deuxieme vue');
        $response->assertSee('Vue isolee');
        $this->assertSame(3, substr_count($response->getContent(), '<figcaption'));

        // Trois images : deux colonnes, la premiere en pleine largeur pour
        // qu'aucune ne reste orpheline sur la derniere ligne.
        $this->assertSame(1, substr_count($response->getContent(), 'sm:col-span-2'));

        // La visionneuse du gabarit est cablee sur chaque image.
        $this->assertSame(3, substr_count($response->getContent(), 'popup-image'));
    }

    public function test_the_author_name_is_never_shown_on_the_public_site(): void
    {
        $auteur = User::factory()->create(['name' => 'Prenom Nomdefamille']);
        $post = Post::factory()->create([
            'title' => 'Actualite signee',
            'user_id' => $auteur->id,
        ]);

        // Ni sur la liste des actualites, ni sur la fiche.
        $this->get(route('posts.index'))->assertOk()->assertDontSee('Prenom Nomdefamille');
        $this->get(route('posts.show', $post))->assertOk()->assertDontSee('Prenom Nomdefamille');

        // La date de publication reste affichee.
        $this->get(route('posts.show', $post))
            ->assertSee($post->published_at->translatedFormat('d F Y'), false);
    }

    public function test_a_post_without_gallery_renders_no_gallery_block(): void
    {
        $post = Post::factory()->create(['title' => 'Actualite sans galerie']);

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertDontSee('post-gallery');
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
