<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Icône d'onglet du site.
 *
 * Elle était déclarée sur un `favicon.svg` absent du dépôt : le navigateur
 * n'affichait donc rien. Ces tests gardent la déclaration et les fichiers
 * ensemble — l'une sans les autres se voit mal et se remarque tard.
 */
class FaviconTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{int}>
     */
    public static function taillesDeclarees(): array
    {
        return ['onglet' => [32], 'apple' => [180], 'android' => [192]];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('taillesDeclarees')]
    public function test_each_declared_size_exists_on_disk(int $taille): void
    {
        $fichier = public_path("assets/imgs/template/logo/favicon-{$taille}.png");

        $this->assertFileExists($fichier);

        [$largeur, $hauteur] = getimagesize($fichier);

        $this->assertSame($taille, $largeur);
        $this->assertSame($taille, $hauteur);
    }

    public function test_the_public_site_declares_the_icon(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('favicon-32.png', $contenu);
        $this->assertStringContainsString('rel="apple-touch-icon"', $contenu);
    }

    public function test_the_back_office_declares_it_too(): void
    {
        $contenu = $this->get('/login')->assertOk()->getContent();

        $this->assertStringContainsString('favicon-32.png', $contenu);
    }

    public function test_no_view_points_at_a_missing_image_in_the_logo_folder(): void
    {
        /*
         * Trois gabarits visaient `favicon.svg`, absent du dossier : l'icône
         * d'onglet et deux logos restaient vides sans que rien ne le signale.
         */
        $absents = [];

        foreach (glob(resource_path('views/**/*.blade.php')) + glob(resource_path('views/**/**/*.blade.php')) as $vue) {
            preg_match_all("#assets/imgs/template/logo/([\w.-]+)#", (string) file_get_contents($vue), $trouves);

            foreach ($trouves[1] as $image) {
                if (! file_exists(public_path('assets/imgs/template/logo/'.$image))) {
                    $absents[] = basename($vue).' → '.$image;
                }
            }
        }

        $this->assertSame([], array_unique($absents));
    }

    public function test_the_tab_icon_stays_light_enough_to_ship(): void
    {
        // L'original fait 622 Ko : servi tel quel, il serait telecharge a
        // chaque page pour etre affiche en 16 pixels.
        $this->assertLessThan(
            10 * 1024,
            filesize(public_path('assets/imgs/template/logo/favicon-32.png')),
        );
    }
}
