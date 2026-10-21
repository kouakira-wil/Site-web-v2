<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Section « Votre parcours » de la page L'école : en-tête, carte des pôles et
 * étapes du cursus, toutes saisies au back-office.
 */
class AboutParcoursTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\SchoolJourneySeeder::class);
    }

    private function page(): string
    {
        return $this->get('/l-ecole')->assertOk()->getContent();
    }

    public function test_the_section_renders_its_header_card_and_steps(): void
    {
        $contenu = $this->page();

        $this->assertStringContainsString('Votre parcours', $contenu);
        $this->assertStringContainsString('Technologie et Gestion', $contenu);
        $this->assertSame(3, substr_count($contenu, 'journey-list__item'));

        foreach (['CAP', 'Licence', 'Master'] as $cycle) {
            $this->assertStringContainsString($cycle, $contenu);
        }
    }

    public function test_the_texts_come_from_the_back_office(): void
    {
        PageHeader::forKey('ecole.parcours.2')->forceFill([
            'eyebrow' => 'Licence remaniée',
            'title' => 'Un titre saisi en base',
        ])->save();

        $contenu = $this->page();

        $this->assertStringContainsString('Un titre saisi en base', $contenu);
        $this->assertStringNotContainsString('Approfondir et se spécialiser', $contenu);
    }

    public function test_a_step_emptied_of_its_title_disappears(): void
    {
        PageHeader::forKey('ecole.parcours.3')->forceFill(['title' => ''])->save();

        // Un titre vide laisserait une puce et une flèche sans rien à lire.
        $this->assertSame(2, substr_count($this->page(), 'journey-list__item'));
    }

    public function test_the_card_disappears_without_a_title(): void
    {
        PageHeader::forKey('ecole.parcours.pole')->forceFill(['title' => ''])->save();

        $this->assertStringNotContainsString('alt-portfolio-thumb', $this->page());
    }

    public function test_an_empty_button_label_removes_the_button(): void
    {
        PageHeader::forKey('ecole.parcours')->forceFill(['cta_label' => null])->save();

        $this->assertStringNotContainsString('Demander une', $this->page());
    }

    public function test_the_steps_do_not_leak_into_the_identity_section(): void
    {
        /*
         * « Ce qui nous guide » balaie tout le groupe `ecole` en excluant les
         * clés des sections qui ont leur propre gabarit. Sans exclusion, les
         * cinq blocs du parcours s'y afficheraient une seconde fois.
         */
        // La grille de l'identité ne retient que les blocs du groupe `ecole`
        // qui ont un texte. Les cinq blocs du parcours en ont un : sans
        // l'exclusion, ils viendraient grossir cette grille.
        PageHeader::forKey('ecole.objectif')->forceFill(['intro' => 'Un objectif.'])->save();

        $contenu = $this->page();

        $this->assertSame(1, substr_count($contenu, 'programme-col__title'));
        $this->assertSame(3, substr_count($contenu, 'journey-list__title'));
    }

    public function test_the_step_links_announce_where_they_go(): void
    {
        // Une flèche seule ne dit rien : le libellé reprend l'intitulé de l'étape.
        $this->assertStringContainsString(
            'aria-label="En savoir plus : Entrer rapidement dans le métier"',
            $this->page(),
        );
    }
}
