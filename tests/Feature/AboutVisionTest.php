<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\PageHeader;
use App\Models\User;
use App\Support\PageHeaderSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AboutVisionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\AboutIntroSeeder::class);
    }

    private function vision(): PageHeader
    {
        return PageHeader::query()->where('key', 'ecole.vision')->firstOrFail();
    }

    public function test_an_empty_vision_renders_nothing(): void
    {
        // Sans texte ni étape, mieux vaut rien qu'une section creuse.
        PageHeader::query()->where('key', 'like', 'ecole.vision%')->delete();

        $this->get('/l-ecole')->assertOk()->assertDontSee('vision-grid');
    }

    public function test_the_three_steps_are_rendered_in_order(): void
    {
        $this->seed(\Database\Seeders\VisionStepsSeeder::class);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertSame(3, substr_count($contenu, 'vision-etape__titre'));

        foreach (['Formation théorique', 'Pratique professionnelle', 'Carrière et insertion'] as $etiquette) {
            $this->assertStringContainsString($etiquette, $contenu);
        }

        // L'ordre suit la progression : connaissances, compétences, expérience.
        $this->assertLessThan(
            strpos($contenu, 'Les compétences'),
            strpos($contenu, 'Les connaissances'),
        );
        $this->assertLessThan(
            strpos($contenu, 'L’expérience professionnelle'),
            strpos($contenu, 'Les compétences'),
        );
    }

    public function test_a_step_is_editable_on_its_own_page(): void
    {
        $this->seed(\Database\Seeders\VisionStepsSeeder::class);
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $etape = PageHeader::query()->where('key', 'ecole.vision.2')->firstOrFail();

        Livewire::test(PageHeaderForm::class, ['header' => $etape])
            ->assertSee('Étiquette')
            ->assertSee('Titre de l’étape')
            ->set('title', 'Un intitule tout neuf')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()->assertSee('Un intitule tout neuf');
    }

    public function test_the_section_appears_once_filled(): void
    {
        $this->vision()->update([
            'eyebrow' => 'Ce qui nous guide',
            'intro' => 'Une phrase de vision.',
            'icon' => 'compass',
            'tags' => ['Un point', 'Un autre point'],
            'cta_label' => 'Nos filieres',
        ]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('vision-grid', $contenu);
        $this->assertStringContainsString('Ce qui nous guide', $contenu);
        $this->assertStringContainsString('Une phrase de vision.', $contenu);
        $this->assertSame(2, substr_count($contenu, 'vision-points__item'));
        $this->assertStringContainsString('Nos filieres', $contenu);
    }

    public function test_it_sits_between_the_metrics_strip_and_the_mission(): void
    {
        $this->vision()->update(['intro' => 'Une vision.']);
        PageHeader::query()->where('key', 'ecole.mission')->update(['tags' => ['Une mission']]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertLessThan(strpos($contenu, 'vision-grid'), strpos($contenu, 'reperes-grid'));
        $this->assertLessThan(strpos($contenu, 'mission-split'), strpos($contenu, 'vision-grid'));
    }

    public function test_the_vision_is_not_repeated_in_the_identity_grid(): void
    {
        $this->vision()->update(['title' => 'Notre vision', 'intro' => 'Une vision.']);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertSame(1, substr_count($contenu, 'Notre vision'));
    }

    public function test_the_block_offers_its_own_fields_in_the_back_office(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $champs = PageHeaderSchema::fieldsFor($this->vision());
        $this->assertContains('image_secondary', $champs);
        $this->assertContains('icon', $champs);

        Livewire::test(PageHeaderForm::class, ['header' => $this->vision()])
            ->assertSee('Texte de la vision')
            ->assertSee('Première photo')
            ->assertSee('Seconde photo')
            ->set('intro', 'Une vision saisie au back-office.')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()->assertSee('Une vision saisie au back-office.');
    }
}
