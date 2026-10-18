<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La section « filieres phares » de l’accueil presente desormais les filieres
 * par le texte : surtitre, titre en deux teintes, accroche, paragraphe,
 * bouton et medaillon, tous saisis au back-office.
 */
class HomeFilieresHighlightTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\HomeSectionSeeder::class);
    }

    private function accueil(): string
    {
        return $this->get(route('home'))->assertOk()->getContent();
    }

    public function test_the_section_shows_every_text_of_its_block(): void
    {
        $bloc = PageHeader::forKey('home.portfolio');
        $contenu = $this->accueil();

        foreach (['eyebrow', 'title', 'title_accent', 'intro', 'body', 'cta_label'] as $champ) {
            $this->assertStringContainsString(
                e($bloc->{$champ}),
                $contenu,
                "Le champ {$champ} du bloc ne ressort pas sur la page.",
            );
        }

        // La fin du titre est reprise en couleur : sans cette classe, les deux
        // moities se confondent.
        $this->assertStringContainsString('phare__accent', $contenu);
    }

    public function test_the_texts_come_from_the_back_office(): void
    {
        PageHeader::forKey('home.portfolio')->forceFill([
            'title' => 'Un titre saisi en base',
            'title_accent' => 'et sa suite colorée',
        ])->save();

        $contenu = $this->accueil();

        $this->assertStringContainsString('Un titre saisi en base', $contenu);
        $this->assertStringContainsString('et sa suite colorée', $contenu);
        $this->assertStringNotContainsString('Des filières construites avec', $contenu);
    }

    public function test_the_medallion_disappears_without_its_text(): void
    {
        $this->assertStringContainsString('phare__pastille', $this->accueil());

        PageHeader::forKey('home.portfolio')->forceFill(['tags' => []])->save();

        // Une pastille vide tournerait dans le vide : on ne la rend pas.
        $this->assertStringNotContainsString('phare__pastille', $this->accueil());
    }

    public function test_an_empty_button_label_removes_the_button(): void
    {
        PageHeader::forKey('home.portfolio')->forceFill(['cta_label' => null])->save();

        $this->assertStringNotContainsString('phare__bouton', $this->accueil());
    }
}
