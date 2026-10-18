<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Préchargeur du site public.
 *
 * Un voile en `position: fixed` et `z-index: 99999` rend le site inutilisable
 * s'il ne part pas. Ces tests gardent les filets de sécurité en place, pas
 * l'animation elle-même.
 */
class PreloaderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function pagesPubliques(): array
    {
        return [
            'accueil' => ['/'],
            'l’école' => ['/l-ecole'],
            'filières' => ['/filieres'],
            'journal' => ['/journal'],
            'contact' => ['/contact'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pagesPubliques')]
    public function test_every_public_page_carries_the_preloader(string $url): void
    {
        $this->assertStringContainsString('data-prechargeur', $this->get($url)->assertOk()->getContent());
    }

    public function test_it_sits_at_the_very_top_of_the_body(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        // Plus bas, il n'aurait plus rien à masquer : le début de la page
        // serait déjà peint quand il arrive.
        $this->assertLessThan(
            strpos($contenu, 'id="smooth-wrapper"'),
            strpos($contenu, 'data-prechargeur'),
        );
    }

    public function test_it_is_hidden_outright_without_javascript(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        // Sans script, aucun des deux filets ne peut l'effacer.
        $this->assertMatchesRegularExpression(
            '#<noscript>\s*<style>\.page-loader \{ display: none !important; \}</style>\s*</noscript>#',
            $contenu,
        );
    }

    public function test_an_inline_script_removes_it_even_if_the_bundle_fails(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        /*
         * Ce filet doit rester en ligne : chargé depuis le bundle, il
         * disparaîtrait avec lui, c'est-à-dire exactement quand il sert.
         */
        $voile = substr($contenu, (int) strpos($contenu, 'data-prechargeur'));
        $voile = substr($voile, 0, 2000);

        $this->assertStringContainsString('<script>', $voile);
        $this->assertStringContainsString('.remove()', $voile);
    }

    public function test_it_stays_out_of_the_accessibility_tree(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '#class="page-loader"[^>]*aria-hidden="true"#',
            $contenu,
        );
    }

    public function test_it_draws_one_panel_per_tenth_of_the_screen(): void
    {
        // Les volets font 10vw chacun : moins de dix laisseraient voir la page.
        $contenu = $this->get('/')->assertOk()->getContent();

        $voile = substr($contenu, (int) strpos($contenu, 'data-prechargeur'));
        $voile = substr($voile, 0, (int) strpos($voile, 'page-loader-logo'));

        $this->assertSame(10, substr_count($voile, 'class="bar"'));
    }

    public function test_the_back_office_has_none(): void
    {
        // Le tableau de bord a sa propre mise en page ; un voile du site public
        // y resterait pour toujours, faute de script pour l'effacer.
        $contenu = $this->get('/login')->assertOk()->getContent();

        $this->assertStringNotContainsString('data-prechargeur', $contenu);
    }
}
