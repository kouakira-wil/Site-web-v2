<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Cycle;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Page /filieres, groupée par cycle.
 *
 * Une filière ouverte sur deux cycles paraît dans les deux groupes : c'est le
 * même parcours, proposé à deux niveaux. Et une filière qu'aucun cycle ne
 * porte doit rester visible plutôt que de disparaître sans bruit.
 */
class FilieresParCycleTest extends TestCase
{
    use RefreshDatabase;

    private function page(): string
    {
        return $this->get('/filieres')->assertOk()->getContent();
    }

    /**
     * Les fiches rendues à l'intérieur d'un groupe.
     */
    private function fichesDuGroupe(string $contenu, string $cle): int
    {
        $debut = strpos($contenu, 'data-groupe-cycle="'.$cle.'"');

        if ($debut === false) {
            return 0;
        }

        $bloc = substr($contenu, $debut);
        $suivant = strpos($bloc, 'data-groupe-cycle="', 10);

        return substr_count($suivant === false ? $bloc : substr($bloc, 0, $suivant), 'mg-portfolio-item');
    }

    private function filiere(string $titre, array $cycles, ?Category $pole = null): Project
    {
        $filiere = Project::factory()->create(['title' => $titre, 'category_id' => $pole?->id]);
        $filiere->cycles()->attach(collect($cycles)->pluck('id')->all());

        return $filiere;
    }

    public function test_each_cycle_forms_its_own_group(): void
    {
        $cap = Cycle::query()->create(['name' => 'CAP', 'slug' => 'cap', 'short_label' => 'CAP', 'position' => 0]);
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);

        $this->filiere('Froid et Climatisation', [$cap]);
        $this->filiere('Intelligence Artificielle', [$licence]);

        $contenu = $this->page();

        $this->assertStringContainsString('data-groupe-cycle="cap"', $contenu);
        $this->assertStringContainsString('data-groupe-cycle="cycle-licence"', $contenu);
        $this->assertSame(1, $this->fichesDuGroupe($contenu, 'cap'));
        $this->assertSame(1, $this->fichesDuGroupe($contenu, 'cycle-licence'));
    }

    public function test_the_groups_follow_the_order_of_the_cycles(): void
    {
        Cycle::query()->create(['name' => 'Cycle Master', 'slug' => 'cycle-master', 'short_label' => 'BAC +5', 'position' => 2]);
        $cap = Cycle::query()->create(['name' => 'CAP', 'slug' => 'cap', 'short_label' => 'CAP', 'position' => 0]);
        $master = Cycle::query()->where('slug', 'cycle-master')->firstOrFail();

        $this->filiere('Une filiere du CAP', [$cap]);
        $this->filiere('Une filiere du Master', [$master]);

        $contenu = $this->page();

        // Du plus court au plus long : c'est l'ordre des cycles, pas celui des
        // filières, qui commande la page.
        $this->assertLessThan(
            strpos($contenu, 'data-groupe-cycle="cycle-master"'),
            strpos($contenu, 'data-groupe-cycle="cap"'),
        );
    }

    public function test_a_filiere_open_at_two_levels_appears_in_both(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);
        $master = Cycle::query()->create(['name' => 'Cycle Master', 'slug' => 'cycle-master', 'short_label' => 'BAC +5', 'position' => 2]);

        $this->filiere('Génie Informatique et Réseaux', [$licence, $master]);

        $contenu = $this->page();

        $this->assertSame(1, $this->fichesDuGroupe($contenu, 'cycle-licence'));
        $this->assertSame(1, $this->fichesDuGroupe($contenu, 'cycle-master'));
    }

    public function test_a_filiere_without_any_cycle_is_still_shown(): void
    {
        /*
         * Sans ce dernier groupe, une filière publiée mais rattachée à aucun
         * cycle disparaîtrait de la page sans que rien ne le signale.
         */
        Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);

        Project::factory()->create(['title' => 'Filiere sans cycle']);

        $contenu = $this->page();

        $this->assertStringContainsString('data-groupe-cycle="sans-cycle"', $contenu);
        $this->assertStringContainsString('Filiere sans cycle', $contenu);
    }

    public function test_an_empty_cycle_gets_no_heading(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);
        Cycle::query()->create(['name' => 'Cycle Desert', 'slug' => 'cycle-desert', 'short_label' => 'ZZZ', 'position' => 3]);

        $this->filiere('Une filiere', [$licence]);

        // Un titre de cycle au-dessus du vide ne dit rien d'utile.
        $this->assertStringNotContainsString('data-groupe-cycle="cycle-desert"', $this->page());
    }

    public function test_each_group_announces_how_many_it_holds(): void
    {
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);

        $this->filiere('Premiere', [$licence]);
        $this->filiere('Seconde', [$licence]);

        $this->assertStringContainsString('2 filières', $this->page());
    }

    public function test_the_pole_filter_survives_the_grouping(): void
    {
        // Le filtre balaie tous les groupes : chaque fiche garde son pôle.
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);
        $pole = Category::factory()->create(['name' => 'Pôle Technologie', 'slug' => 'pole-technologie', 'type' => Category::TYPE_PROJECT]);

        $this->filiere('Réseaux et Télécommunications', [$licence], $pole);

        $contenu = $this->page();

        $this->assertStringContainsString('data-filter="pole-technologie"', $contenu);
        $this->assertStringContainsString('data-category="pole-technologie"', $contenu);
    }

    public function test_the_page_no_longer_lists_the_cycles_twice(): void
    {
        /*
         * La page portait aussi un bloc « cycle par cycle » en bas. Avec la
         * grille désormais groupée, il répétait la même chose.
         */
        $licence = Cycle::query()->create(['name' => 'Cycle Licence', 'slug' => 'cycle-licence', 'short_label' => 'BAC +3', 'position' => 1]);

        $this->filiere('Une filiere', [$licence]);

        $contenu = $this->page();

        // Une seule fiche rendue (le titre, lui, paraît aussi dans l'`alt` et
        // le libellé du lien de cette même fiche).
        $this->assertSame(1, substr_count($contenu, 'mg-portfolio-item'));
        $this->assertStringNotContainsString('sd3-tracks__title', $contenu);
    }
}
