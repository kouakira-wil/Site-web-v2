<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Cycle;
use App\Models\Project;
use App\View\Components\Site\FilieresMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Sous-menu « Filières » de l'en-tête.
 *
 * Le gabarit donnait à chaque colonne la moitié du panneau. À trois cycles,
 * les colonnes totalisaient une fois et demie sa largeur et débordaient par
 * la droite. Elles tiennent désormais sur une seule ligne, une par cycle.
 */
class FilieresMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(FilieresMenu::CACHE_KEY);
    }

    private function cycle(string $nom, string $slug, int $position): Cycle
    {
        return Cycle::query()->create(['name' => $nom, 'slug' => $slug, 'short_label' => $slug, 'position' => $position]);
    }

    private function page(): string
    {
        return $this->get('/filieres')->assertOk()->getContent();
    }

    public function test_the_panel_holds_one_column_per_cycle(): void
    {
        foreach ([['CAP', 'cap', 0], ['Cycle Licence', 'cycle-licence', 1], ['Cycle Master', 'cycle-master', 2]] as [$nom, $slug, $rang]) {
            $cycle = $this->cycle($nom, $slug, $rang);
            Project::factory()->create(['title' => 'Filiere '.$slug])->cycles()->attach($cycle);
        }

        $contenu = $this->page();

        $this->assertSame(3, substr_count($contenu, 'filieres-menu__colonne'));

        /*
         * Le nombre de colonnes passe à la grille par une propriété
         * personnalisée : sans elle, la grille retombe sur trois colonnes et
         * la mise en page se décale dès qu'un cycle s'ajoute ou disparaît.
         */
        $this->assertStringContainsString('--filieres-menu-colonnes: 3', $contenu);
    }

    public function test_the_column_count_follows_the_cycles(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        Project::factory()->create()->cycles()->attach($licence);

        $this->assertStringContainsString('--filieres-menu-colonnes: 1', $this->page());
    }

    public function test_the_columns_name_their_cycle(): void
    {
        // « BAC +3 » seul ne dit pas de quel cycle il s'agit.
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        Project::factory()->create()->cycles()->attach($licence);

        $this->assertStringContainsString('>Cycle Licence</span>', $this->page());
    }

    public function test_a_cycle_without_a_published_filiere_gets_no_column(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $vide = $this->cycle('Cycle Desert', 'cycle-desert', 3);

        Project::factory()->create()->cycles()->attach($licence);
        Project::factory()->create(['status' => Project::STATUS_DRAFT, 'published_at' => null])->cycles()->attach($vide);

        $contenu = $this->page();

        $this->assertSame(1, substr_count($contenu, 'filieres-menu__colonne'));
        $this->assertStringNotContainsString('Cycle Desert', $contenu);
    }

    public function test_without_any_cycle_it_stays_a_plain_link(): void
    {
        // Un panneau vide qui s'ouvre vaut moins qu'un lien direct.
        $contenu = $this->page();

        $this->assertStringNotContainsString('filieres-menu__grille', $contenu);
    }

    public function test_the_menu_cache_is_cleared_when_a_filiere_changes(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $filiere = Project::factory()->create(['title' => 'Avant changement']);
        $filiere->cycles()->attach($licence);

        $this->assertStringContainsString('Avant changement', $this->page());

        // Le menu est en cache sur chaque page : sans purge, il montrerait
        // longtemps un intitulé qui n'existe plus.
        $filiere->update(['title' => 'Apres changement']);

        $contenu = $this->page();

        $this->assertStringContainsString('Apres changement', $contenu);
        $this->assertStringNotContainsString('Avant changement', $contenu);
    }
}
