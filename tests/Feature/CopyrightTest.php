<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Mention de copyright des pieds de page.
 *
 * L'année y était écrite en dur : elle devenait fausse au premier janvier,
 * sans que rien ne le signale.
 */
class CopyrightTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function pagesPubliques(): array
    {
        return ['accueil' => ['/'], 'contact' => ['/contact'], 'filières' => ['/filieres']];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('pagesPubliques')]
    public function test_it_shows_the_current_year(string $url): void
    {
        Carbon::setTestNow(Carbon::parse('2031-06-15 12:00:00', 'UTC'));

        $contenu = $this->get($url)->assertOk()->getContent();

        $this->assertStringContainsString('<time datetime="2031">2031</time>', $contenu);
    }

    public function test_no_page_carries_a_hardcoded_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2031-06-15 12:00:00', 'UTC'));

        $contenu = $this->get('/')->assertOk()->getContent();

        // L'ancienne mention, figée, ne doit plus apparaître nulle part.
        $this->assertStringNotContainsString('ESCAT © 2026', $contenu);
    }

    public function test_the_year_turns_over_on_the_first_of_january(): void
    {
        // 20 h UTC, soit 21 h à Brazzaville : on est encore le 31 décembre.
        Carbon::setTestNow(Carbon::parse('2030-12-31 20:00:00', 'UTC'));
        $this->assertStringContainsString('>2030</time>', $this->get('/')->assertOk()->getContent());

        Carbon::setTestNow(Carbon::parse('2031-01-01 06:00:00', 'UTC'));
        $this->assertStringContainsString('>2031</time>', $this->get('/')->assertOk()->getContent());
    }

    public function test_it_reads_the_year_in_the_school_timezone(): void
    {
        /*
         * L'application tourne en UTC ; Brazzaville a une heure d'avance. Le
         * 31 décembre à 23 h UTC, il est déjà le 1er janvier à l'école — et
         * c'est sa date qui doit figurer sur son site.
         */
        $this->assertSame('UTC', config('app.timezone'));
        $this->assertSame('Africa/Brazzaville', config('ecole.fuseau'));

        Carbon::setTestNow(Carbon::parse('2030-12-31 23:30:00', 'UTC'));

        $this->assertStringContainsString('>2031</time>', $this->get('/')->assertOk()->getContent());
    }

    public function test_a_creation_year_turns_it_into_a_range(): void
    {
        Carbon::setTestNow(Carbon::parse('2031-06-15 12:00:00', 'UTC'));
        config(['ecole.annee_creation' => 2024]);

        $this->assertStringContainsString('2024–2031', $this->get('/')->assertOk()->getContent());
    }

    public function test_the_first_year_shows_alone(): void
    {
        // Une plage « 2031–2031 » n'aurait aucun sens.
        Carbon::setTestNow(Carbon::parse('2031-06-15 12:00:00', 'UTC'));
        config(['ecole.annee_creation' => 2031]);

        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('>2031</time>', $contenu);
        $this->assertStringNotContainsString('2031–2031', $contenu);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }
}
