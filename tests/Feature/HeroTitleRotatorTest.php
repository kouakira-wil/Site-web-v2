<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HeroTitleRotatorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\HomeSectionSeeder::class);
    }

    private function hero(): PageHeader
    {
        return PageHeader::query()->where('key', 'home.hero')->firstOrFail();
    }

    public function test_without_variants_the_title_falls_back_to_its_second_line(): void
    {
        $hero = $this->hero();
        $hero->update(['title_variants' => [], 'title_accent' => 'devenir un leader de demain.']);

        $this->assertSame(['devenir un leader de demain.'], $hero->fresh()->titleVariants());

        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('devenir un leader de demain.', $contenu);
        // Une seule ligne : rien à faire défiler, le script ne s'accroche pas.
        $this->assertStringNotContainsString('data-hero-rotator', $contenu);
    }

    public function test_each_variant_is_rendered_and_only_the_first_is_active(): void
    {
        $this->hero()->update(['title_variants' => ['Premiere ligne', 'Deuxieme ligne', 'Troisieme ligne']]);

        $contenu = $this->get('/')->assertOk()->getContent();

        $this->assertSame(3, substr_count($contenu, 'hero-rotator__item'));
        $this->assertSame(1, substr_count($contenu, 'hero-rotator__item is-active'));
        $this->assertStringContainsString('data-hero-rotator', $contenu);

        foreach (['Premiere ligne', 'Deuxieme ligne', 'Troisieme ligne'] as $variante) {
            $this->assertStringContainsString($variante, $contenu);
        }
    }

    public function test_the_carousel_is_hidden_from_assistive_technology(): void
    {
        $this->hero()->update(['title_variants' => ['Premiere ligne', 'Deuxieme ligne']]);

        $contenu = $this->get('/')->assertOk()->getContent();

        // Le nom accessible du titre doit rester stable : une seule variante est
        // lue, le carrousel visuel est masqué.
        $this->assertStringContainsString('<span class="hero-rotator" aria-hidden="true"', $contenu);
        $this->assertStringContainsString('<span class="sr-only">Premiere ligne</span>', $contenu);
    }

    public function test_blank_lines_are_dropped(): void
    {
        $this->hero()->update(['title_variants' => ['Une ligne', '   ', '', 'Une autre']]);

        $this->assertSame(['Une ligne', 'Une autre'], $this->hero()->fresh()->titleVariants());
    }

    public function test_staff_can_edit_the_variants_one_per_line(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        Livewire::test(PageHeaderForm::class, ['header' => $this->hero()])
            ->set('title_variants', "Saisie une\nSaisie deux")
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(['Saisie une', 'Saisie deux'], $this->hero()->fresh()->titleVariants());

        $this->get('/')->assertOk()->assertSee('Saisie deux');
    }
}
