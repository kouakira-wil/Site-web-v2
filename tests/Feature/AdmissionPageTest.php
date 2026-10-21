<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\PageHeader;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Page Admission, qui a remplacé la page Équipe.
 *
 * Quatre sections : critères par cycle, processus, programmes et FAQ. Tout le
 * texte vient du back-office ; le tableau des programmes, lui, se construit
 * sur les filières et les cycles réellement publiés.
 */
class AdmissionPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\AdmissionSeeder::class);
    }

    private function page(): string
    {
        return $this->get('/admission')->assertOk()->getContent();
    }

    private function filiere(string $titre, Cycle $cycle, ?Category $pole = null): Project
    {
        $filiere = Project::factory()->create([
            'title' => $titre,
            'category_id' => $pole?->id,
            'outcomes' => "Premier métier\nDeuxième métier",
        ]);

        $filiere->cycles()->attach($cycle);

        return $filiere;
    }

    public function test_the_four_sections_are_on_the_page(): void
    {
        // La section des programmes ne paraît qu'avec des filières à montrer.
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'short_label' => 'BAC +3', 'position' => 1]);
        $this->filiere('Réseaux et Télécommunications', $licence);

        $contenu = $this->page();

        foreach ([
            'Critères et Processus d’Admission',
            'Processus d’Admission',
            'Nos Programmes Académiques',
            'Foire aux Questions',
        ] as $titre) {
            $this->assertStringContainsString($titre, $contenu);
        }
    }

    public function test_the_programmes_section_hides_without_any_filiere(): void
    {
        // Des onglets sans ligne à révéler valent mieux absents.
        $this->assertStringNotContainsString('Nos Programmes Académiques', $this->page());
    }

    public function test_the_old_team_url_redirects_instead_of_breaking(): void
    {
        // Les liens déjà partagés vers /equipe doivent continuer de mener
        // quelque part, pas sur une page d'erreur.
        $this->get('/equipe')->assertRedirect('/admission')->assertStatus(301);
    }

    public function test_the_menu_points_at_admission(): void
    {
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('>Admission</a>', $contenu);
    }

    public function test_the_criteria_panels_come_from_their_blocks(): void
    {
        PageHeader::forKey('admission.criteres.2')->forceFill(['title' => 'Un volet saisi en base'])->save();

        $contenu = $this->page();

        $this->assertStringContainsString('Un volet saisi en base', $contenu);
        $this->assertSame(4, substr_count($contenu, 'id="critere-'));
    }

    public function test_a_panel_without_a_title_disappears(): void
    {
        PageHeader::forKey('admission.criteres.3')->forceFill(['title' => ''])->save();

        $this->assertSame(3, substr_count($this->page(), 'id="critere-'));
    }

    public function test_an_empty_panel_says_so_instead_of_opening_on_nothing(): void
    {
        // Les critères du CAP ne sont pas encore rédigés : le volet existe et
        // renvoie vers le secrétariat plutôt que de s'ouvrir sur du vide.
        $this->assertStringContainsString('seront précisées prochainement', $this->page());
    }

    public function test_the_steps_are_numbered_by_their_order(): void
    {
        PageHeader::forKey('admission.processus.5')->forceFill(['position' => 19])->save();

        $contenu = $this->page();

        $this->assertMatchesRegularExpression(
            '#Finalisation de l’inscription.*?Étape 1.*?Vérifier les critères#s',
            $contenu,
        );
    }

    public function test_each_accordion_keeps_its_own_panel_identifiers(): void
    {
        /*
         * Les deux accordéons cohabitent sur la page. Un identifiant partagé
         * ferait ouvrir le volet des critères en cliquant sur une question.
         */
        $contenu = $this->page();

        $criteres = [];
        $faq = [];

        preg_match_all('#id="(critere-\d+)"#', $contenu, $criteres);
        preg_match_all('#id="(faq-admission-\d+)"#', $contenu, $faq);

        $this->assertSame([], array_intersect($criteres[1], $faq[1]));
        $this->assertNotEmpty($criteres[1]);
        $this->assertNotEmpty($faq[1]);
    }

    public function test_the_table_lists_every_published_filiere_with_its_cycles(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'short_label' => 'BAC +3', 'position' => 1]);
        $pole = Category::factory()->create(['name' => 'Pôle Technologie', 'slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);

        $this->filiere('Réseaux et Télécommunications', $licence, $pole);

        $contenu = $this->page();

        $this->assertStringContainsString('Réseaux et Télécommunications', $contenu);
        $this->assertStringContainsString('data-cycles="cycle-licence"', $contenu);
        $this->assertStringContainsString('data-pole="pole-technologie"', $contenu);

        // Les débouchés sont saisis une ligne par métier et rendus sur une seule.
        $this->assertStringContainsString('Premier métier, Deuxième métier', $contenu);
    }

    public function test_a_cycle_without_any_filiere_gets_no_tab(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'short_label' => 'BAC +3', 'position' => 1]);
        Cycle::query()->create(['name' => 'Cycle Desert', 'short_label' => 'ZZZ', 'position' => 2]);

        $this->filiere('Réseaux et Télécommunications', $licence);

        $contenu = $this->page();

        // Un onglet vide ne révélerait aucune ligne.
        $this->assertStringContainsString('data-cycle="cycle-licence"', $contenu);
        $this->assertStringNotContainsString('data-cycle="cycle-desert"', $contenu);
    }

    public function test_a_draft_filiere_stays_out_of_the_table(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'short_label' => 'BAC +3', 'position' => 1]);

        $brouillon = Project::factory()->create([
            'title' => 'Filiere brouillon',
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);
        $brouillon->cycles()->attach($licence);

        $this->assertStringNotContainsString('Filiere brouillon', $this->page());
    }

    public function test_the_table_is_whole_before_any_filtering(): void
    {
        /*
         * Le tri se joue dans le navigateur : si le serveur ne renvoyait que
         * le premier cycle, les autres onglets n'auraient rien à révéler.
         */
        $cap = Cycle::query()->create(['name' => 'CAP', 'short_label' => 'CAP', 'position' => 0]);
        $master = Cycle::query()->create(['name' => 'Cycle Master', 'short_label' => 'BAC +5', 'position' => 2]);

        $this->filiere('Filiere du CAP', $cap);
        $this->filiere('Filiere du Master', $master);

        $contenu = $this->page();

        $this->assertStringContainsString('Filiere du CAP', $contenu);
        $this->assertStringContainsString('Filiere du Master', $contenu);
    }
}
