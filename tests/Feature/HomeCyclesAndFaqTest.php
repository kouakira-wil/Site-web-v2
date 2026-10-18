<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Deux sections de l'accueil désormais tenues depuis le back-office : les
 * cartes de cycles de « Nos formations », et les questions fréquentes.
 */
class HomeCyclesAndFaqTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\HomeSectionSeeder::class);
        $this->seed(\Database\Seeders\HomeCyclesSeeder::class);
        $this->seed(\Database\Seeders\HomeFaqSeeder::class);
    }

    private function accueil(): string
    {
        return $this->get(route('home'))->assertOk()->getContent();
    }

    public function test_the_cycle_cards_come_from_their_blocks(): void
    {
        $contenu = $this->accueil();

        $this->assertSame(3, substr_count($contenu, 'at-service-item'));

        foreach (['CAP', 'Cycle Licence (BAC +3)', 'Cycle Master (BAC +5)'] as $cycle) {
            $this->assertStringContainsString($cycle, $contenu);
        }
    }

    public function test_the_card_numbering_follows_the_order(): void
    {
        // La numérotation est calculée : réordonner suffit à la renuméroter,
        // sans retoucher au gabarit.
        PageHeader::forKey('home.services.3')->forceFill(['position' => 30])->save();

        $contenu = $this->accueil();

        $this->assertMatchesRegularExpression(
            '#\[01\].*?Cycle Master.*?\[02\].*?CAP#s',
            $contenu,
        );
    }

    public function test_a_card_emptied_of_its_title_disappears(): void
    {
        PageHeader::forKey('home.services.2')->forceFill(['title' => ''])->save();

        $this->assertSame(2, substr_count($this->accueil(), 'at-service-item'));
    }

    public function test_the_left_stack_mirrors_the_card_thumbnails(): void
    {
        PageHeader::forKey('home.services.1')->forceFill(['image' => 'entetes/un.jpg'])->save();
        PageHeader::forKey('home.services.3')->forceFill(['image' => 'entetes/trois.jpg'])->save();

        $contenu = $this->accueil();

        /*
         * Le script du gabarit apparie les cartes et les vignettes par leur
         * rang. Il faut donc autant d'emplacements que de cartes, même pour
         * celles sans photo : en sauter un décalerait tous les suivants et le
         * survol révélerait la mauvaise image.
         */
        $this->assertSame(3, substr_count($contenu, 'hover-image'));

        // Chaque photo paraît deux fois : dans la pile et sur sa carte.
        $this->assertSame(2, substr_count($contenu, 'entetes/un.jpg'));
        $this->assertSame(2, substr_count($contenu, 'entetes/trois.jpg'));
    }

    public function test_no_image_tag_is_left_without_a_source(): void
    {
        // Les visuels du gabarit ont été remplacés par de vraies photos : plus
        // aucun chemin en dur ne doit subsister dans cette section.
        $contenu = $this->accueil();
        $section = substr($contenu, (int) strpos($contenu, 'at-service-area'));
        $section = substr($section, 0, (int) strpos($section, 'at-service-area-end'));

        $this->assertStringNotContainsString('-sm.webp', $section);
        $this->assertDoesNotMatchRegularExpression('#<img[^>]*src=""#', $section);
    }

    public function test_the_counter_reads_its_block(): void
    {
        PageHeader::forKey('home.services')->forceFill(['tags' => ['24 | Promotions']])->save();

        $contenu = $this->accueil();

        $this->assertStringContainsString('data-count="24"', $contenu);
        $this->assertStringContainsString('Promotions', $contenu);
    }

    public function test_an_empty_counter_removes_it(): void
    {
        PageHeader::forKey('home.services')->forceFill(['tags' => []])->save();

        // Le bandeau de chiffres a son propre odomètre : on ne regarde que
        // la colonne de gauche de la section des formations.
        $contenu = $this->accueil();
        $section = substr($contenu, (int) strpos($contenu, 'at-service-sales-wrap'));
        $section = substr($section, 0, (int) strpos($section, 'at-service-list-wrap'));

        $this->assertStringNotContainsString('odometer', $section);
    }

    public function test_the_faq_renders_one_panel_per_question(): void
    {
        $contenu = $this->accueil();

        $this->assertSame(4, substr_count($contenu, 'at-faq-item'));
        $this->assertStringContainsString('Comment soumettre un dossier ?', $contenu);
        $this->assertStringContainsString('Service des admissions', $contenu);
    }

    public function test_each_question_gets_its_own_panel_identifier(): void
    {
        /*
         * Le script du gabarit relie le bouton au panneau par cet identifiant.
         * Deux questions qui partagent le même ouvriraient le même panneau.
         * Trois attributs le portent : la cible du bouton, `aria-controls` et
         * l'`id` du panneau.
         */
        $contenu = $this->accueil();

        foreach (range(1, 4) as $rang) {
            $this->assertSame(3, substr_count($contenu, 'faq-reponse-'.$rang.'"'));
        }
    }

    public function test_only_the_first_question_opens_on_load(): void
    {
        $contenu = $this->accueil();

        $this->assertSame(1, substr_count($contenu, 'at-collapse is-open'));
        $this->assertSame(3, substr_count($contenu, 'at-faq-button collapsed'));
    }

    public function test_a_question_emptied_of_its_title_disappears(): void
    {
        PageHeader::forKey('home.faq.3')->forceFill(['title' => ''])->save();

        $this->assertSame(3, substr_count($this->accueil(), 'at-faq-item'));
    }

    public function test_the_new_blocks_do_not_leak_into_other_sections(): void
    {
        // Les blocs enfants portent le préfixe de leur parent : une section qui
        // balaierait le groupe sans filtrer les afficherait une seconde fois.
        $contenu = $this->accueil();

        $this->assertSame(1, substr_count($contenu, 'Comment soumettre un dossier ?'));
        $this->assertSame(1, substr_count($contenu, 'Une question sans réponse'));
    }
}
