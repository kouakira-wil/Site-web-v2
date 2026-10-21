<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Liens vers les réseaux de l'école.
 *
 * Un réseau non renseigné retombe sur la page de contact : l'icône reste
 * visible et mène ailleurs que là où elle promet. Ces tests gardent les
 * réseaux réellement ouverts hors de ce repli.
 */
class SocialLinksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function pagesPubliques(): array
    {
        return ['accueil' => ['/'], 'contact' => ['/contact'], 'l’école' => ['/l-ecole'], 'filières' => ['/filieres']];
    }

    public function test_the_accounts_are_configured(): void
    {
        $this->assertSame('https://www.tiktok.com/@.escat', config('social.tiktok'));
        $this->assertSame('https://www.instagram.com/universite_escat/', config('social.instagram'));
        $this->assertNotEmpty(config('social.facebook'));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pagesPubliques')]
    public function test_every_page_offers_the_same_four_networks(string $url): void
    {
        /*
         * La liste était recopiée dans cinq gabarits, avec des ensembles
         * différents : chaque page montrait autre chose.
         */
        $contenu = $this->get($url)->assertOk()->getContent();

        foreach ([config('social.facebook'), config('social.instagram'), config('social.tiktok'), 'https://wa.me/'.config('ecole.whatsapp')] as $lien) {
            $this->assertStringContainsString($lien, $contenu, "{$url} n’offre pas {$lien}.");
        }
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pagesPubliques')]
    public function test_no_page_links_an_account_the_school_does_not_have(string $url): void
    {
        // Twitter, LinkedIn et YouTube n'ont pas de compte : leurs liens
        // retombaient sur /contact, sous un libellé qui promettait autre chose.
        $contenu = $this->get($url)->assertOk()->getContent();

        foreach (['Twitter', 'Linkedin', 'LinkedIn', 'Youtube'] as $absent) {
            $this->assertStringNotContainsString($absent, $contenu, "{$url} propose encore {$absent}.");
        }
    }

    public function test_an_unset_network_disappears_everywhere(): void
    {
        config(['social.instagram' => '']);

        $this->assertStringNotContainsString('Instagram', $this->get('/')->assertOk()->getContent());
    }

    public function test_the_tiktok_link_announces_tiktok(): void
    {
        // Le lien du menu mobile portait le libellé et le logo de YouTube.
        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '#href="https://www\.tiktok\.com/@\.escat"[^>]*aria-label="TikTok"#',
            $contenu,
        );
    }

    public function test_every_outbound_social_link_opens_safely(): void
    {
        /*
         * `target="_blank"` sans `rel="noopener"` donne à la page ouverte une
         * poignée sur celle-ci via `window.opener`.
         */
        $contenu = $this->get('/')->assertOk()->getContent();

        preg_match_all('#<a[^>]*href="https://www\.(tiktok|facebook)\.com[^"]*"[^>]*>#', $contenu, $liens);

        $this->assertNotEmpty($liens[0]);

        foreach ($liens[0] as $lien) {
            $this->assertStringContainsString('rel="noopener noreferrer"', $lien);
        }
    }
}
