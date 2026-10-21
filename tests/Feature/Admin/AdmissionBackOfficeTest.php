<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Livewire\Admin\Pages\PageHeaderIndex;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Back-office de la page Admission.
 *
 * La famille « Page Équipe » a disparu avec sa page : ses blocs restaient
 * modifiables alors que plus rien ne les rendait. Ces tests gardent le
 * sommaire aligné sur ce que le site affiche réellement.
 */
class AdmissionBackOfficeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\AdmissionSeeder::class);

        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    private function bloc(string $cle): PageHeader
    {
        return PageHeader::query()->where('key', $cle)->firstOrFail();
    }

    public function test_the_summary_lists_the_admission_family(): void
    {
        Livewire::test(PageHeaderIndex::class)
            ->assertSee('Page Admission')
            ->assertSee('Critères — conditions générales')
            ->assertSee('Processus — étape 1')
            ->assertSee('FAQ — question 1');
    }

    public function test_the_summary_no_longer_offers_the_team_page(): void
    {
        Livewire::test(PageHeaderIndex::class)->assertDontSee('Page Équipe');

        $this->assertSame(0, PageHeader::query()->where('key', 'like', 'equipe%')->count());
    }

    public function test_each_kind_of_block_offers_the_fields_its_section_reads(): void
    {
        // Volet de critères : un intitulé et un corps, rien d'autre.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.criteres.1')])
            ->assertSee('Intitulé du volet')
            ->assertSee('Critères')
            ->assertDontSee('Pictogramme');

        // Étape du processus : un pictogramme, pas de texte long.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.processus.1')])
            ->assertSee('Pictogramme')
            ->assertSee('Texte de l’étape');

        // Question : un libellé et une réponse.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.faq.1')])
            ->assertSee('Question')
            ->assertSee('Réponse');

        // En-tête de section : un titre et une accroche.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.programmes')])
            ->assertSee('Titre de la section')
            ->assertSee('Accroche');
    }

    public function test_editing_a_block_changes_the_page(): void
    {
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.faq.2')])
            ->set('title', 'Une question saisie au back-office')
            ->set('body', 'Et sa réponse.')
            ->call('save')
            ->assertHasNoErrors();

        $contenu = $this->get('/admission')->assertOk()->getContent();

        $this->assertStringContainsString('Une question saisie au back-office', $contenu);
        $this->assertStringContainsString('Et sa réponse.', $contenu);
    }

    public function test_every_block_points_at_the_page_that_renders_it(): void
    {
        /*
         * Le lien « Voir la page » du formulaire. Sans règle propre, les blocs
         * d'Admission retombaient sur /contact, où ils ne paraissent pas.
         */
        foreach (PageHeader::query()->where('key', 'like', 'admission%')->get() as $bloc) {
            $this->assertStringEndsWith('/admission', $bloc->publicUrl(), "Le bloc {$bloc->key} renvoie ailleurs.");
        }
    }

    public function test_the_new_step_icons_are_accepted(): void
    {
        // Les pictogrammes du processus ont été ajoutés au catalogue : sans
        // cela, la validation refuserait le bloc tel qu'il est semé.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('admission.processus.3')])
            ->set('icon', 'send')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('send', $this->bloc('admission.processus.3')->icon);
    }
}
