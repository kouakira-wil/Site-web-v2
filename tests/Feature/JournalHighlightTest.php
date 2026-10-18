<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La page Actualités présente toutes les actualités d'un seul tenant :
 * ni mise en avant séparée, ni pagination. Le tri par rubrique se joue
 * dans le navigateur, sans rechargement.
 */
class JournalHighlightTest extends TestCase
{
    use RefreshDatabase;

    private function articles(int $nombre): void
    {
        foreach (range(1, $nombre) as $i) {
            Post::factory()->create([
                'title' => "Article {$i}",
                'slug' => "article-{$i}",
                'published_at' => now()->subDays($nombre - $i),
            ]);
        }
    }

    /**
     * @return array<string, int>
     */
    private function liensParArticle(string $url): array
    {
        $contenu = $this->get($url)->assertOk()->getContent();
        $comptes = [];

        preg_match_all('#/journal/(article-[0-9]+)"#', $contenu, $trouves);

        foreach ($trouves[1] as $slug) {
            $comptes[$slug] = ($comptes[$slug] ?? 0) + 1;
        }

        return $comptes;
    }

    public function test_every_published_article_is_shown_at_once(): void
    {
        $this->articles(14);

        // Sans pagination, les quatorze doivent tenir sur la page.
        $this->assertCount(14, $this->liensParArticle('/journal'));
    }

    public function test_no_article_appears_twice(): void
    {
        $this->articles(6);

        // Chaque carte porte deux liens — l'image et le titre. Un article
        // affiché deux fois en porterait quatre.
        foreach ($this->liensParArticle('/journal') as $slug => $liens) {
            $this->assertLessThanOrEqual(2, $liens, "L’article {$slug} apparaît plusieurs fois.");
        }
    }

    public function test_the_filter_sits_in_the_top_block(): void
    {
        $this->articles(3);
        Category::factory()->create(['name' => 'Evenements', 'slug' => 'evenements', 'type' => Category::TYPE_POST]);

        $contenu = $this->get('/journal')->assertOk()->getContent();

        // Il précède la grille, dans le premier bloc de la page.
        $this->assertLessThan(
            strpos($contenu, 'sec-2-archive-1'),
            strpos($contenu, 'filter-portfolio'),
        );

        // Le bandeau « Dernières actualités » a bien disparu.
        $this->assertStringNotContainsString('Dernières actualités', $contenu);
    }

    public function test_the_filter_sorts_without_reloading_the_page(): void
    {
        $this->articles(3);
        $rubrique = Category::factory()->create(['name' => 'Evenements', 'slug' => 'evenements', 'type' => Category::TYPE_POST]);
        Post::query()->where('slug', 'article-1')->update(['category_id' => $rubrique->id]);

        $contenu = $this->get('/journal')->assertOk()->getContent();

        /*
         * `initPortfolioFilter()` du gabarit s'accroche à
         * `.filter-portfolio .filter-btn` et masque les fiches dont le
         * `data-category` ne correspond pas au `data-filter` cliqué. Aucun
         * lien vers le serveur : la page ne se recharge pas.
         */
        $this->assertStringContainsString('filter-portfolio', $contenu);
        $this->assertStringContainsString('data-filter="all"', $contenu);
        $this->assertStringContainsString('data-filter="evenements"', $contenu);
        $this->assertStringNotContainsString('categorie=evenements', $contenu);

        // Les trois articles sont déjà là : le filtre n'a rien à aller chercher.
        $this->assertCount(3, $this->liensParArticle('/journal'));
        // Chaque fiche annonce sa rubrique : sans `data-category`, le script
        // du gabarit la masquerait dès le premier clic sur un pôle.
        $this->assertSame(1, substr_count($contenu, 'data-category="evenements"'));
        $this->assertSame(3, substr_count($contenu, 'data-category='));
    }

    public function test_the_active_filter_is_announced(): void
    {
        $this->articles(2);
        Category::factory()->create(['name' => 'Evenements', 'slug' => 'evenements', 'type' => Category::TYPE_POST]);

        $contenu = $this->get('/journal')->assertOk()->getContent();

        /*
         * La classe `active` colore le bouton ; seule `aria-pressed` le dit
         * aux lecteurs d'écran. `initFiltreAccessible()` la tient à jour au
         * clic, encore faut-il qu'elle soit posée au chargement.
         */
        $this->assertMatchesRegularExpression(
            '#data-filter="all"[^>]*aria-pressed="true"#s',
            $contenu,
        );
        $this->assertMatchesRegularExpression(
            '#data-filter="evenements"[^>]*aria-pressed="false"#s',
            $contenu,
        );
    }

    public function test_a_draft_stays_out_of_the_list(): void
    {
        $this->articles(3);
        Post::factory()->create([
            'title' => 'Brouillon',
            'slug' => 'article-99',
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->assertArrayNotHasKey('article-99', $this->liensParArticle('/journal'));
    }
}
