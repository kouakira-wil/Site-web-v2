<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderManager;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DirectorWordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\DirectorWordSeeder::class);
    }

    public function test_the_card_renders_its_lead_and_paragraphs(): void
    {
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('Mot du Directeur', $contenu);
        $this->assertStringContainsString('bienvenue à l’ESCAT', $contenu);
        // Deux paragraphes dans le corps dépliable.
        $this->assertSame(2, substr_count($contenu, 'directeur-card__text'));
    }

    public function test_the_body_starts_collapsed(): void
    {
        $contenu = $this->get('/l-ecole')->getContent();

        // Sans la classe `is-open`, le panneau démarre replié et le bouton
        // annonce « Voir plus ».
        $this->assertStringContainsString('aria-expanded="false"', $contenu);
        $this->assertStringNotContainsString('directeur-card__suite at-collapse is-open', $contenu);
        $this->assertStringContainsString('Voir plus', $contenu);
        $this->assertStringContainsString('Voir moins', $contenu);
    }

    public function test_a_blank_line_separates_two_paragraphs(): void
    {
        $mot = PageHeader::query()->where('key', 'ecole.directeur')->firstOrFail();
        $mot->update(['body' => "Premier bloc.\nToujours le premier.\n\nDeuxieme bloc."]);

        $paragraphes = $mot->refresh()->bodyParagraphs();

        $this->assertCount(2, $paragraphes);
        $this->assertSame('Premier bloc. Toujours le premier.', $paragraphes[0]);
    }

    public function test_the_section_disappears_when_emptied(): void
    {
        PageHeader::query()->where('key', 'ecole.directeur')->update(['intro' => null, 'body' => null]);

        $this->get('/l-ecole')->assertOk()->assertDontSee('directeur-card');
    }

    public function test_the_director_word_is_not_repeated_in_the_identity_section(): void
    {
        // Les deux partagent le groupe « ecole » : la section identité doit
        // écarter cette clé, sinon le mot du directeur s'afficherait deux fois.
        PageHeader::query()->where('key', 'ecole.vision')->update(['intro' => 'Une vision de controle.']);

        $contenu = $this->get('/l-ecole')->getContent();

        $this->assertSame(1, substr_count($contenu, 'Mot du Directeur'));
        $this->assertStringContainsString('Une vision de controle.', $contenu);
    }

    public function test_staff_can_rewrite_the_message(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $index = PageHeader::query()->orderBy('group')->orderBy('position')->orderBy('id')
            ->pluck('key')->search('ecole.directeur');

        Livewire::test(PageHeaderManager::class)
            ->set("headers.{$index}.body", "Un tout nouveau paragraphe.\n\nEt un second.")
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()->assertSee('Un tout nouveau paragraphe.');
    }
}
