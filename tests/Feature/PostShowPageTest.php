<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Page d'une actualité.
 *
 * Les boutons de partage portaient le logo d'un réseau et le libellé d'un
 * autre : l'icône X menait sur Facebook, annoncée « Twitter ». Et le bloc
 * « Dernières actualités » ne proposait que deux articles de la même rubrique.
 */
class PostShowPageTest extends TestCase
{
    use RefreshDatabase;

    private function articles(int $nombre, ?Category $rubrique = null): void
    {
        foreach (range(1, $nombre) as $i) {
            Post::factory()->create([
                'title' => "Article {$i}",
                'slug' => "article-{$i}",
                'category_id' => $rubrique?->id,
                'published_at' => now()->subDays($nombre - $i),
            ]);
        }
    }

    private function page(string $slug = 'article-1'): string
    {
        return $this->get('/journal/'.$slug)->assertOk()->getContent();
    }

    private function basDePage(string $slug = 'article-1'): string
    {
        $contenu = $this->page($slug);

        return substr($contenu, (int) strpos($contenu, 'sec-2-blog-details'));
    }

    public function test_each_share_button_matches_the_network_it_names(): void
    {
        $this->articles(1);

        $contenu = $this->page();

        $this->assertMatchesRegularExpression(
            '#href="https://www\.facebook\.com/sharer[^"]*"[^>]*aria-label="Partager sur Facebook"#',
            $contenu,
        );
        $this->assertMatchesRegularExpression(
            '#href="https://wa\.me/\?text=[^"]*"[^>]*aria-label="Partager sur WhatsApp"#',
            $contenu,
        );
    }

    public function test_it_no_longer_offers_networks_the_school_does_not_use(): void
    {
        $this->articles(1);

        $contenu = $this->page();

        // Twitter et LinkedIn restaient des boutons de partage alors que
        // l'école n'a de compte sur ni l'un ni l'autre.
        $this->assertStringNotContainsString('twitter.com', $contenu);
        $this->assertStringNotContainsString('linkedin.com', $contenu);
    }

    public function test_the_link_can_be_copied_for_the_networks_without_web_sharing(): void
    {
        /*
         * Instagram et TikTok n'offrent aucune adresse de partage web :
         * copier le lien est ce qui s'en approche le plus.
         */
        $this->articles(1);

        $contenu = $this->page();

        $this->assertStringContainsString('data-copier-lien="'.url('/journal/article-1').'"', $contenu);
        $this->assertStringContainsString('aria-label="Copier le lien de l’article"', $contenu);
    }

    public function test_the_shared_address_is_the_article_itself(): void
    {
        $this->articles(2);

        $contenu = $this->page('article-2');

        $this->assertStringContainsString(urlencode(url('/journal/article-2')), $contenu);
        $this->assertStringNotContainsString(urlencode(url('/journal/article-1')).'"', $contenu);
    }

    public function test_the_bottom_block_holds_every_other_article(): void
    {
        $this->articles(6);

        // Cinq cartes : les six publiés, moins celui qu'on lit.
        $this->assertSame(5, substr_count($this->basDePage(), 'class="blog-card '));
    }

    public function test_it_no_longer_stops_at_the_same_category(): void
    {
        $vie = Category::factory()->create(['name' => 'Vie de l’école', 'slug' => 'vie', 'type' => Category::TYPE_POST]);
        $evenements = Category::factory()->create(['name' => 'Événements', 'slug' => 'evenements', 'type' => Category::TYPE_POST]);

        Post::factory()->create(['title' => 'Le lu', 'slug' => 'le-lu', 'category_id' => $vie->id, 'published_at' => now()]);
        Post::factory()->create(['title' => 'Autre rubrique', 'slug' => 'autre', 'category_id' => $evenements->id, 'published_at' => now()->subDay()]);

        $this->assertStringContainsString('Autre rubrique', $this->basDePage('le-lu'));
    }

    public function test_the_article_being_read_is_not_among_them(): void
    {
        $this->articles(4);

        $bas = $this->basDePage('article-3');

        $this->assertSame(3, substr_count($bas, 'class="blog-card '));
        $this->assertStringNotContainsString('/journal/article-3"', $bas);
    }

    public function test_they_run_from_the_most_recent(): void
    {
        $this->articles(4);

        preg_match_all('#blog-card__title-link">([^<]+)#', $this->basDePage('article-1'), $titres);

        $this->assertSame(['Article 4', 'Article 3', 'Article 2'], $titres[1]);
    }

    public function test_a_lone_article_says_so(): void
    {
        $this->articles(1);

        // « Pas d'autre actualité dans cette rubrique » était trompeur : il
        // n'y en avait pas d'autre du tout.
        $this->assertStringContainsString('la seule actualité publiée', $this->basDePage());
    }

    public function test_a_draft_stays_out_of_the_bottom_block(): void
    {
        $this->articles(2);

        Post::factory()->create([
            'title' => 'Brouillon',
            'slug' => 'brouillon',
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->assertStringNotContainsString('Brouillon', $this->basDePage());
    }

    public function test_every_image_of_the_block_exists(): void
    {
        Post::factory()->create(['title' => 'Le lu', 'slug' => 'le-lu', 'published_at' => now()]);
        Post::factory()->create(['title' => 'Sans couverture', 'slug' => 'sans', 'cover_image' => null, 'published_at' => now()->subDay()]);

        preg_match_all('#/assets/imgs/pages/([^"]+)#', $this->basDePage('le-lu'), $images);

        $absents = array_values(array_filter(
            array_unique($images[1]),
            fn (string $fichier): bool => ! file_exists(public_path('assets/imgs/pages/'.$fichier)),
        ));

        $this->assertSame([], $absents);
    }
}
