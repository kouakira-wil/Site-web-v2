<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiliereDescriptionTest extends TestCase
{
    use RefreshDatabase;

    private function filiere(array $attributs = []): Project
    {
        return Project::factory()->create($attributs + [
            'status' => Project::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_the_description_appears_on_the_filiere_page(): void
    {
        $filiere = $this->filiere(['description' => 'Un parcours qui mene aux metiers du reseau.']);

        $this->get(route('projects.show', $filiere))
            ->assertOk()
            ->assertSee('Un parcours qui mene aux metiers du reseau.')
            ->assertSee('filiere-presentation__body');
    }

    public function test_markdown_is_rendered(): void
    {
        $filiere = $this->filiere(['description' => "Un **mot important** et une liste :\n\n- Premier point\n- Second point"]);

        $contenu = $this->get(route('projects.show', $filiere))->assertOk()->getContent();

        $this->assertStringContainsString('<strong>mot important</strong>', $contenu);
        $this->assertStringContainsString('<li>Premier point</li>', $contenu);
    }

    public function test_raw_html_from_an_editor_is_stripped(): void
    {
        // Un compte rédacteur compromis ne doit pas pouvoir injecter de balises.
        $filiere = $this->filiere([
            'description' => 'Texte legitime <script>alert(1)</script> et <img src=x onerror=alert(1)>.',
        ]);

        $contenu = $this->get(route('projects.show', $filiere))->assertOk()->getContent();

        $this->assertStringContainsString('Texte legitime', $contenu);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $contenu);
        $this->assertStringNotContainsString('onerror=', $contenu);
    }

    public function test_an_empty_description_renders_no_block(): void
    {
        $filiere = $this->filiere(['description' => null]);

        $this->get(route('projects.show', $filiere))
            ->assertOk()
            ->assertDontSee('filiere-presentation');
    }
}
