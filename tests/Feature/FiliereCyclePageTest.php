<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\PageHeader;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Page d'un cycle : /filieres/cap, /filieres/cycle-licence…
 *
 * Elle ne montre que les filières de ce niveau. Les intitulés de cycle du
 * méga-menu, de la liste complète et des pieds de page y mènent.
 */
class FiliereCyclePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget(\App\View\Components\Site\FilieresMenu::CACHE_KEY);
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
    }

    private function cycle(string $nom, string $slug, int $rang, ?string $niveau = null): Cycle
    {
        return Cycle::query()->create([
            'name' => $nom,
            'slug' => $slug,
            'short_label' => $niveau ?? $slug,
            'position' => $rang,
        ]);
    }

    private function filiere(string $titre, Cycle $cycle, ?Category $pole = null): Project
    {
        $filiere = Project::factory()->create(['title' => $titre, 'category_id' => $pole?->id]);
        $filiere->cycles()->attach($cycle);

        return $filiere;
    }

    /**
     * Le corps de la page seul. Le méga-menu de l'en-tête liste toutes les
     * filières de tous les cycles, et les pieds de page tous les cycles :
     * chercher dans la page entière ne dirait rien de ce qu'elle montre.
     */
    private function corps(string $url): string
    {
        $contenu = $this->get($url)->assertOk()->getContent();
        $contenu = substr($contenu, (int) strpos($contenu, 'sec-1-portfolio-1'));

        return substr($contenu, 0, (int) strpos($contenu, 'at-footer'));
    }

    public function test_it_shows_only_the_filieres_of_that_cycle(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1, 'BAC +3');
        $master = $this->cycle('Cycle Master', 'cycle-master', 2, 'BAC +5');

        $this->filiere('Intelligence Artificielle', $licence);
        $this->filiere('Ingenierie Financiere', $master);

        $corps = $this->corps('/filieres/cycle-licence');

        $this->assertStringContainsString('Intelligence Artificielle', $corps);
        $this->assertStringNotContainsString('Ingenierie Financiere', $corps);
        $this->assertSame(1, substr_count($corps, 'mg-portfolio-item'));
    }

    public function test_a_filiere_open_at_two_levels_shows_on_both_pages(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $master = $this->cycle('Cycle Master', 'cycle-master', 2);

        $filiere = Project::factory()->create(['title' => 'Genie Informatique']);
        $filiere->cycles()->attach([$licence->id, $master->id]);

        $this->assertStringContainsString('Genie Informatique', $this->get('/filieres/cycle-licence')->assertOk()->getContent());
        $this->assertStringContainsString('Genie Informatique', $this->get('/filieres/cycle-master')->assertOk()->getContent());
    }

    public function test_the_page_is_titled_after_the_cycle(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1, 'BAC +3');
        $licence->update(['description' => 'Trois ans pour approfondir un domaine.']);
        $this->filiere('Une filiere', $licence);

        $contenu = $this->get('/filieres/cycle-licence')->assertOk()->getContent();

        // Le bandeau est celui de la page Filières, aux textes du cycle.
        $this->assertStringContainsString('>Cycle Licence</span>', $contenu);
        $this->assertStringContainsString('BAC +3', $contenu);
        $this->assertStringContainsString('Trois ans pour approfondir un domaine.', $contenu);

        // Et il ne reprend pas le titre de la liste complète.
        $this->assertStringNotContainsString(PageHeader::forKey('filieres')->title_accent, $contenu);
    }

    public function test_the_breadcrumb_goes_back_through_the_full_list(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $this->filiere('Une filiere', $licence);

        $contenu = $this->get('/filieres/cycle-licence')->assertOk()->getContent();

        $this->assertMatchesRegularExpression(
            '#<nav class="sd3-crumb[^>]*>.*?Accueil.*?Fili\#res.*?Cycle Licence#su',
            str_replace('è', '#', $contenu),
        );
    }

    public function test_an_unknown_cycle_is_not_found(): void
    {
        $this->get('/filieres/cycle-inconnu')->assertNotFound();
    }

    public function test_a_draft_filiere_stays_out(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);

        $brouillon = Project::factory()->create([
            'title' => 'Filiere brouillon',
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);
        $brouillon->cycles()->attach($licence);

        $this->assertStringNotContainsString('Filiere brouillon', $this->get('/filieres/cycle-licence')->assertOk()->getContent());
    }

    public function test_the_pole_filter_only_appears_with_more_than_one_pole(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $techno = Category::factory()->create(['name' => 'Pôle Technologie', 'slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);

        $this->filiere('Premiere', $licence, $techno);

        /*
         * Avec un seul pôle, « Tous » et le bouton du pôle révéleraient la
         * même chose : le filtre ne sert à rien.
         */
        $this->assertStringNotContainsString('filter-portfolio', $this->get('/filieres/cycle-licence')->assertOk()->getContent());

        $gestion = Category::factory()->create(['name' => 'Pôle Gestion', 'slug' => 'pole-gestion', 'type' => Category::TYPE_PROJECT]);
        $this->filiere('Seconde', $licence, $gestion);

        $contenu = $this->get('/filieres/cycle-licence')->assertOk()->getContent();

        $this->assertStringContainsString('data-filter="pole-technologie"', $contenu);
        $this->assertStringContainsString('data-filter="pole-gestion"', $contenu);
    }

    public function test_it_offers_the_other_cycles(): void
    {
        $cap = $this->cycle('CAP', 'cap', 0);
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $vide = $this->cycle('Cycle Desert', 'cycle-desert', 3);

        $this->filiere('Une du CAP', $cap);
        $this->filiere('Une de Licence', $licence);

        $corps = $this->corps('/filieres/cycle-licence');

        $this->assertStringContainsString(route('projects.cycle', $cap), $corps);
        // Ni le cycle courant, ni un cycle sans filière publiée : la
        // passerelle ne propose que des destinations qui changent la page.
        $this->assertStringNotContainsString(route('projects.cycle', $vide), $corps);
        $this->assertStringNotContainsString(route('projects.cycle', $licence).'"', $corps);
    }

    public function test_the_full_list_links_to_each_cycle_page(): void
    {
        $cap = $this->cycle('CAP', 'cap', 0);
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);

        $this->filiere('Une du CAP', $cap);
        $this->filiere('Une de Licence', $licence);

        $contenu = $this->get('/filieres')->assertOk()->getContent();

        foreach ([$cap, $licence] as $cycle) {
            $this->assertStringContainsString(route('projects.cycle', $cycle), $contenu);
        }
    }

    public function test_the_menu_links_to_each_cycle_page(): void
    {
        $licence = $this->cycle('Cycle Licence', 'cycle-licence', 1);
        $this->filiere('Une de Licence', $licence);

        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString(route('projects.cycle', $licence), $contenu);
    }

    public function test_an_empty_cycle_says_so_instead_of_breaking(): void
    {
        $this->cycle('Cycle Licence', 'cycle-licence', 1);

        $contenu = $this->get('/filieres/cycle-licence')->assertOk()->getContent();

        $this->assertStringContainsString('Aucune filière n’est ouverte', $contenu);
    }
}
