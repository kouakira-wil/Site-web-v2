<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Section « Actualités et événements » de l'accueil.
 *
 * Elle laissait trois `<div>` ouverts : tout ce qui suivait sur la page —
 * l'appel à l'action et le pied de page compris — se retrouvait imbriqué
 * dedans.
 */
class HomeJournalTest extends TestCase
{
    use RefreshDatabase;

    private function articles(int $nombre): void
    {
        foreach (range(1, $nombre) as $i) {
            Post::factory()->create([
                'title' => "Article {$i}",
                'slug' => "article-{$i}",
                // Le plus grand indice est le plus récent.
                'published_at' => now()->subDays($nombre - $i),
            ]);
        }
    }

    private function section(): string
    {
        $contenu = $this->get('/')->assertOk()->getContent();
        $contenu = substr($contenu, (int) strpos($contenu, 'at-sec13-area-start'));

        return substr($contenu, 0, (int) strpos($contenu, 'at-sec13-area-end'));
    }

    public function test_the_section_closes_every_tag_it_opens(): void
    {
        $this->articles(4);

        $section = $this->section();

        $this->assertSame(
            substr_count($section, '<div'),
            substr_count($section, '</div'),
            'La section laisse des balises ouvertes.',
        );
    }

    public function test_the_footer_is_no_longer_nested_inside_it(): void
    {
        $this->articles(4);

        // Conséquence directe des balises non fermées : le pied de page
        // tombait à l'intérieur de la section des actualités.
        $this->assertStringNotContainsString('at-footer', $this->section());
    }

    public function test_it_shows_four_blocks(): void
    {
        $this->articles(8);

        $this->assertSame(4, substr_count($this->section(), 'class="blog-card '));
    }

    public function test_they_are_the_most_recent_ones(): void
    {
        $this->articles(6);

        $section = $this->section();

        // Les quatre derniers publiés, du plus récent au plus ancien.
        preg_match_all('#blog-card__title-link">([^<]+)#', $section, $titres);

        $this->assertSame(['Article 6', 'Article 5', 'Article 4', 'Article 3'], $titres[1]);
    }

    public function test_the_row_asks_for_equal_heights(): void
    {
        /*
         * Sans `items-stretch`, chaque carte s'arrêtait à la hauteur de son
         * propre titre et les dates ne s'alignaient pas.
         */
        $this->articles(4);

        $this->assertStringContainsString('!items-stretch', $this->section());
    }

    public function test_an_article_without_a_cover_falls_back_on_an_image_that_exists(): void
    {
        /*
         * Les visuels du gabarit (img-23.webp à img-26.webp) ne sont pas dans
         * le dépôt : une actualité sans couverture y affichait un cadre cassé.
         */
        Post::factory()->create(['title' => 'Sans couverture', 'cover_image' => null]);

        preg_match_all('#/assets/imgs/pages/([^"]+)#', $this->section(), $images);

        $absents = array_values(array_filter(
            array_unique($images[1]),
            fn (string $fichier): bool => ! file_exists(public_path('assets/imgs/pages/'.$fichier)),
        ));

        $this->assertSame([], $absents);
    }

    public function test_an_empty_journal_says_so(): void
    {
        $this->assertStringContainsString('Aucune actualité publiée', $this->section());
    }

    public function test_a_draft_stays_out(): void
    {
        $this->articles(3);

        Post::factory()->create([
            'title' => 'Brouillon',
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->assertStringNotContainsString('Brouillon', $this->section());
    }
}
