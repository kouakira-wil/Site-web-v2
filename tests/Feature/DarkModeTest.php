<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Mode sombre.
 *
 * L'interrupteur existait depuis le portage, mais trois choses le rendaient
 * inoffensif : le gabarit écrivait `data-bs-theme="light"` en dur sur <html>,
 * et color-modes.js fait primer cet attribut sur le choix enregistré ; les
 * blocs ajoutés dans brand.css peignaient leurs couleurs en dur ; et deux tons
 * du gabarit n'avaient pas suivi l'inversion.
 *
 * Ces tests tiennent le contrat de la palette plutôt que son rendu : qu'un
 * jeton existe des deux côtés, qu'il ne se référence pas lui-même, et que les
 * couleurs de texte restent lisibles sur le fond sombre.
 */
class DarkModeTest extends TestCase
{
    use RefreshDatabase;

    /** Fond de page en mode sombre : `--at-neutral-dark-0` de main.css. */
    private const FOND_SOMBRE = '#0C0C0C';

    /** Seuil WCAG AA pour du texte de taille courante. */
    private const CONTRASTE_MINIMUM = 4.5;

    /**
     * Le bleu de marque est le même dans les deux thèmes : il sert d'aplat,
     * où il reste parfaitement lisible. Seul son emploi en texte se décline,
     * sous le nom `--escat-accent-texte`.
     */
    private const PARTAGES = ['--escat-brand', '--escat-brand-dark'];

    private function feuille(): string
    {
        return (string) file_get_contents(resource_path('css/brand.css'));
    }

    /**
     * Les déclarations `--escat-*: valeur` d'un bloc donné.
     *
     * @return array<string, string>
     */
    private function jetons(string $selecteur): array
    {
        $feuille = $this->feuille();
        $debut = strpos($feuille, $selecteur.' {');

        $this->assertNotFalse($debut, "Le bloc {$selecteur} est introuvable.");

        $bloc = substr($feuille, $debut, (int) strpos($feuille, "\n}", $debut) - $debut);

        preg_match_all('/^\s*(--escat-[a-z0-9-]+)\s*:\s*([^;]+);/m', $bloc, $trouves, PREG_SET_ORDER);

        $jetons = [];

        foreach ($trouves as $trouve) {
            $jetons[$trouve[1]] = trim($trouve[2]);
        }

        return $jetons;
    }

    public function test_the_page_no_longer_forces_the_light_theme(): void
    {
        /*
         * L'attribut écrit en dur l'emportait sur le choix enregistré : le
         * mode sombre tenait jusqu'au premier changement de page.
         */
        $this->assertStringNotContainsString('data-bs-theme="light"', $this->get('/')->assertOk()->getContent());
    }

    public function test_the_saved_theme_is_applied_before_the_first_paint(): void
    {
        $contenu = $this->get('/')->assertOk()->getContent();

        $script = strpos($contenu, "localStorage.getItem('theme')");
        $style = strpos($contenu, '<link rel="stylesheet"');

        $this->assertNotFalse($script, 'Le thème enregistré n’est jamais relu.');
        // Après les feuilles, le thème s'appliquerait une fois la page peinte
        // en clair : le visiteur verrait un éclair blanc à chaque page.
        $this->assertLessThan($style, $script);
    }

    public function test_a_first_visit_follows_the_system_preference(): void
    {
        $this->assertStringContainsString('prefers-color-scheme: dark', $this->get('/')->assertOk()->getContent());
    }

    public function test_every_light_token_has_a_dark_counterpart(): void
    {
        $clairs = array_diff(array_keys($this->jetons(':root')), self::PARTAGES);
        $sombres = array_keys($this->jetons('[data-bs-theme=dark]'));

        $this->assertSame([], array_values(array_diff($clairs, $sombres)));
    }

    public function test_no_token_refers_to_itself(): void
    {
        /*
         * `--escat-bordure: var(--escat-bordure)` est accepté par l'analyseur
         * mais rend la variable invalide : la propriété retombe alors sur
         * l'héritage, sans rien signaler. Cinq blocs sont passés au gris des
         * titres de cette façon, en mode clair comme en mode sombre.
         */
        preg_match_all('/^\s*(--escat-[a-z0-9-]+)\s*:\s*var\(\s*\1\s*\)/m', $this->feuille(), $boucles);

        $this->assertSame([], $boucles[1]);
    }

    public function test_every_token_used_is_defined(): void
    {
        preg_match_all('/var\(\s*(--escat-[a-z0-9-]+)/', $this->feuille(), $employes);
        preg_match_all('/^\s*(--escat-[a-z0-9-]+)\s*:/m', $this->feuille(), $definis);

        $this->assertSame([], array_values(array_unique(array_diff($employes[1], $definis[1]))));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function couleursDeTexte(): array
    {
        return [
            'titre' => ['--escat-titre'],
            'titre accentué' => ['--escat-titre-fort'],
            'texte courant' => ['--escat-texte'],
            'texte adouci' => ['--escat-texte-doux'],
            'texte discret' => ['--escat-texte-discret'],
            'texte ténu' => ['--escat-texte-tenu'],
            'accent en texte' => ['--escat-accent-texte'],
            'erreur' => ['--escat-erreur'],
            'erreur en texte' => ['--escat-erreur-texte'],
            'succès' => ['--escat-succes'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('couleursDeTexte')]
    public function test_each_dark_text_colour_stays_readable_on_the_page_background(string $jeton): void
    {
        $couleur = $this->jetons('[data-bs-theme=dark]')[$jeton] ?? null;

        $this->assertNotNull($couleur, "Le jeton {$jeton} n’a pas de version sombre.");

        $contraste = $this->contraste($couleur, self::FOND_SOMBRE);

        $this->assertGreaterThanOrEqual(
            self::CONTRASTE_MINIMUM,
            $contraste,
            sprintf('%s vaut %s, soit %.2f:1 sur le fond sombre.', $jeton, $couleur, $contraste),
        );
    }

    public function test_the_blue_of_the_brand_is_lightened_when_it_carries_text(): void
    {
        /*
         * #2646D0 ne monte qu'à 2,67:1 sur le fond sombre. Il reste l'aplat
         * des boutons — d'où deux jetons, et non une seule valeur basculée.
         */
        $this->assertLessThan(
            self::CONTRASTE_MINIMUM,
            $this->contraste('#2646D0', self::FOND_SOMBRE),
            'Le bleu de marque passerait désormais : les deux jetons n’ont plus lieu d’être.',
        );

        $this->assertNotSame(
            '#2646D0',
            strtoupper($this->jetons('[data-bs-theme=dark]')['--escat-accent-texte']),
        );
    }

    public function test_the_banner_of_the_inner_pages_stays_dark(): void
    {
        /*
         * Le gabarit le peint avec `--sd3-ink`, qui sert aussi d'encre aux
         * titres : en sombre, le bandeau virait au blanc et son titre, blanc
         * lui aussi, disparaissait.
         */
        $this->assertMatchesRegularExpression(
            '/\.sd3-hero__stage\s*\{[^}]*background:\s*var\(--escat-scene\)/',
            $this->feuille(),
        );
    }

    public function test_the_colour_logo_is_not_turned_into_its_negative(): void
    {
        /*
         * main.css retourne l'image en mode sombre, ce qui convient à un
         * logotype d'une seule teinte. Celui de l'école en compte quatre :
         * l'inversion rendait le bleu orange et le vert magenta.
         */
        $feuille = $this->feuille();

        $this->assertMatchesRegularExpression(
            '/\[data-bs-theme=dark\] \.at-header-logo img,[^{]*\{[^}]*filter:\s*none/',
            $feuille,
        );
        $this->assertMatchesRegularExpression(
            '/\[data-bs-theme=dark\] \.at-header-logo img,[^{]*\{[^}]*background:\s*#ffffff/',
            $feuille,
        );
    }

    public function test_the_partner_logos_are_not_turned_into_their_negative(): void
    {
        /*
         * Le gabarit retourne aussi ces images, par `.dark-mode-invert`. Ce
         * sont des logotypes en couleurs sur fond blanc opaque : l'inversion
         * rendait l'orange bleu, le rose vert, et cernait chacun d'un cadre
         * noir. Sur l'accueil le filtre est porté par le conteneur, et un
         * filtre de parent teinte tout son contenu : le lever sur l'image
         * seule ne servirait à rien, d'où deux règles plutôt qu'une.
         */
        $feuille = $this->feuille();

        $this->assertMatchesRegularExpression(
            '/\[data-bs-theme=dark\] \.brand-item\.dark-mode-invert \{[^}]*filter:\s*none/',
            $feuille,
        );
        $this->assertMatchesRegularExpression(
            '/\[data-bs-theme=dark\] \.at-brand-scroll \.brand-logo-slide img,[^{]*\{[^}]*filter:\s*none/',
            $feuille,
        );
    }

    public function test_the_two_tones_the_template_left_uninverted_are_repaired(): void
    {
        /*
         * main.css emploie --at-neutral-700 comme couleur de texte dans 39
         * règles et --at-neutral-300 dans 31, mais leurs valeurs sombres
         * (#535353 et #404040) sont restées sombres.
         */
        $sombre = $this->feuille();
        $debut = strpos($sombre, '[data-bs-theme=dark] {');
        $bloc = substr($sombre, $debut, (int) strpos($sombre, "\n}", $debut) - $debut);

        foreach (['--at-neutral-700', '--at-neutral-300'] as $jeton) {
            preg_match('/'.$jeton.'\s*:\s*([^;]+);/', $bloc, $trouve);

            $this->assertNotEmpty($trouve, "{$jeton} n’est pas corrigé.");
            $this->assertGreaterThanOrEqual(
                self::CONTRASTE_MINIMUM,
                $this->contraste(trim($trouve[1]), self::FOND_SOMBRE),
                "{$jeton} reste illisible sur le fond sombre.",
            );
        }
    }

    public function test_the_preloader_does_not_flash_white(): void
    {
        // Les dix volets du gabarit sont peints en #f3f0f0 : la page s'ouvrait
        // sur un écran presque blanc avant de basculer au noir.
        $this->assertMatchesRegularExpression(
            '/\[data-bs-theme=dark\] \.bar\s*\{[^}]*background:\s*var\(--escat-surface-creuse\)/',
            $this->feuille(),
        );
    }

    /**
     * Rapport de contraste WCAG entre deux couleurs hexadécimales.
     */
    private function contraste(string $premiere, string $seconde): float
    {
        $a = $this->luminance($premiere);
        $b = $this->luminance($seconde);

        return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
    }

    private function luminance(string $hex): float
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        $canaux = [];

        foreach ([0, 2, 4] as $decalage) {
            $valeur = hexdec(substr($hex, $decalage, 2)) / 255;
            $canaux[] = $valeur <= 0.03928 ? $valeur / 12.92 : (($valeur + 0.055) / 1.055) ** 2.4;
        }

        return 0.2126 * $canaux[0] + 0.7152 * $canaux[1] + 0.0722 * $canaux[2];
    }
}
