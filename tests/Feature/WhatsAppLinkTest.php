<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Lien WhatsApp du menu latéral.
 */
class WhatsAppLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_sidebar_offers_whatsapp(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('https://wa.me/'.config('ecole.whatsapp'), $contenu);
    }

    public function test_the_number_is_in_the_shape_wa_me_expects(): void
    {
        /*
         * `wa.me` attend le numéro international collé : ni « + », ni espaces,
         * ni tirets. Le numéro affiché ailleurs sur le site, lui, est espacé
         * pour la lecture — les deux formes coexistent volontairement.
         */
        $this->assertMatchesRegularExpression('/^\d{9,15}$/', (string) config('ecole.whatsapp'));

        $this->assertSame(
            preg_replace('/\D/', '', (string) config('ecole.telephone')),
            (string) config('ecole.whatsapp'),
            'Le numéro WhatsApp et le numéro affiché ne sont pas le même.',
        );
    }

    public function test_the_link_opens_safely_and_says_where_it_goes(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        preg_match('#<a[^>]*href="https://wa\.me/[^"]*"[^>]*>#', $contenu, $lien);

        $this->assertNotEmpty($lien);
        $this->assertStringContainsString('rel="noopener noreferrer"', $lien[0]);

        // Un pictogramme seul ne dit rien : le libellé porte le numéro.
        $this->assertStringContainsString('aria-label="WhatsApp : '.config('ecole.telephone').'"', $lien[0]);
    }
}
