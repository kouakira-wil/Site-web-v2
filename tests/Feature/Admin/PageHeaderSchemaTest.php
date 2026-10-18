<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\PageHeader;
use App\Models\User;
use App\Support\PageHeaderSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageHeaderSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\HomeSectionSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\TeamApproachSeeder::class);
        $this->seed(\Database\Seeders\DirectorWordSeeder::class);

        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    private function bloc(string $cle): PageHeader
    {
        return PageHeader::query()->where('key', $cle)->firstOrFail();
    }

    public function test_a_block_only_exposes_the_fields_its_section_reads(): void
    {
        // Le bandeau défilant n'affiche que des chiffres : proposer un titre,
        // un bouton ou un visuel n'aurait aucun effet sur le site.
        $this->assertSame(['tags', 'label'], PageHeaderSchema::fieldsFor($this->bloc('home.marquee')));

        // Le mot du directeur est le seul bloc à exposer un texte long.
        $this->assertContains('body', PageHeaderSchema::fieldsFor($this->bloc('ecole.directeur')));
        $this->assertNotContains('body', PageHeaderSchema::fieldsFor($this->bloc('home.banner')));

        // Le pictogramme n'a de sens que sur les cartes de la page Équipe.
        $this->assertContains('icon', PageHeaderSchema::fieldsFor($this->bloc('equipe.approche.1')));
        $this->assertNotContains('icon', PageHeaderSchema::fieldsFor($this->bloc('equipe.approche')));

        // Un bloc n'expose un champ photo que s'il en affiche une : le bandeau
        // de chiffres n'en montre aucune, la FAQ en montre une.
        $this->assertContains('image', PageHeaderSchema::fieldsFor($this->bloc('contact')));
        $this->assertContains('image', PageHeaderSchema::fieldsFor($this->bloc('home.faq')));
        $this->assertNotContains('image', PageHeaderSchema::fieldsFor($this->bloc('home.marquee')));
    }

    public function test_the_form_renders_only_those_fields(): void
    {
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('home.marquee')])
            ->assertSee('Chiffres')
            ->assertDontSee('Variantes du titre')
            ->assertDontSee('Texte long')
            ->assertDontSee('Visuel de fond');
    }

    public function test_labels_are_worded_for_the_block_at_hand(): void
    {
        // Le même champ `title` ne désigne pas la même chose partout.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('home.hero')])
            ->assertSee('Titre — première ligne');

        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('equipe.approche.1')])
            ->assertSee('Intitulé de la carte');

        // La section « à propos » a désormais un titre et une accroche distincts.
        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('home.about')])
            ->assertSee('Citation');
    }

    public function test_saving_does_not_wipe_fields_outside_the_schema(): void
    {
        $bloc = $this->bloc('home.faq');

        // Valeurs posées hors formulaire : le schéma de ce bloc ne les expose pas.
        $bloc->forceFill([
            'body' => 'Un texte long conservé.',
            'tags' => ['Une étiquette conservée'],
            'icon' => 'target',
        ])->save();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc->fresh()])
            ->set('title', 'Un titre tout neuf')
            ->call('save')
            ->assertHasNoErrors();

        $apres = $bloc->fresh();

        $this->assertSame('Un titre tout neuf', $apres->title);
        $this->assertSame('Un texte long conservé.', $apres->body);
        $this->assertSame(['Une étiquette conservée'], $apres->tags);
        $this->assertSame('target', $apres->icon);
    }

    public function test_a_field_outside_the_schema_is_not_validated(): void
    {
        // `home.marquee` n'expose pas `title` : une valeur vide ne doit pas
        // bloquer l'enregistrement, alors que ce champ est requis ailleurs.
        $bloc = $this->bloc('home.marquee');
        $bloc->forceFill(['title' => 'Chiffres clés'])->save();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc->fresh()])
            ->set('title', '')
            ->set('tags', '7 | Sept choses')
            ->call('save')
            ->assertHasNoErrors();

        // Le titre en base n'a pas bougé : il n'était pas dans le formulaire.
        $this->assertSame('Chiffres clés', $bloc->fresh()->title);
        $this->assertSame(['7 | Sept choses'], $bloc->fresh()->tags);
    }

    public function test_every_seeded_block_resolves_a_schema(): void
    {
        foreach (PageHeader::query()->get() as $bloc) {
            $champs = PageHeaderSchema::fieldsFor($bloc);

            $this->assertNotEmpty($champs, "Le bloc {$bloc->key} n'a aucun champ.");
            // `label` nomme le bloc dans la liste : il doit rester saisissable.
            $this->assertContains('label', $champs, "Le bloc {$bloc->key} n'expose pas son libellé.");
        }
    }
}
