<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamApproachTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\TeamApproachSeeder::class);
    }

    public function test_the_section_renders_its_four_cards(): void
    {
        $contenu = $this->get('/equipe')->assertOk()->getContent();

        $this->assertStringContainsString('approche-area', $contenu);
        $this->assertSame(4, substr_count($contenu, 'class="approche-card"'));

        foreach (['Notre Approche Pédagogique', 'Approche centrée sur la pratique',
                  'Interaction avec le monde professionnel', 'Suivi personnalisé et mentorat',
                  'Ouverture internationale et numérique'] as $texte) {
            $this->assertStringContainsString($texte, $contenu, "« {$texte} » manque sur la page Équipe.");
        }
    }

    public function test_each_card_carries_its_icon(): void
    {
        $contenu = $this->get('/equipe')->getContent();

        $this->assertSame(4, substr_count($contenu, 'approche-card__icon'));
    }

    public function test_a_card_emptied_of_its_title_disappears(): void
    {
        PageHeader::query()->where('key', 'equipe.approche.4')->update(['title' => '']);

        $contenu = $this->get('/equipe')->getContent();

        $this->assertSame(3, substr_count($contenu, 'class="approche-card"'));
        $this->assertStringNotContainsString('Ouverture internationale', $contenu);
    }

    public function test_editing_a_card_updates_the_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $carte = PageHeader::query()->where('key', 'equipe.approche.1')->firstOrFail();

        Livewire::test(PageHeaderForm::class, ['header' => $carte])
            ->set('title', 'Un intitule tout neuf')
            ->set('icon', 'target')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/equipe')->assertOk()->assertSee('Un intitule tout neuf');
    }

    public function test_an_unknown_icon_name_is_refused(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $carte = PageHeader::query()->where('key', 'equipe.approche.1')->firstOrFail();

        // Un nom libre ne rendrait aucun pictogramme : la liste est fermée.
        Livewire::test(PageHeaderForm::class, ['header' => $carte])
            ->set('icon', 'licorne')
            ->call('save')
            ->assertHasErrors('icon');
    }
}
