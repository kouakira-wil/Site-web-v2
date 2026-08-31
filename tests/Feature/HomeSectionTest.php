<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderManager;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomeSectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\HomeSectionSeeder::class);
    }

    public function test_the_home_page_renders_every_wired_section(): void
    {
        $reponse = $this->get('/');
        $reponse->assertOk();

        // `home.marquee` est absent : sa vignette n'affiche que des chiffres,
        // son titre ne sert que d'étiquette dans le back-office.
        $contenu = $reponse->getContent();

        foreach (['home.hero', 'home.about', 'home.services', 'home.portfolio', 'home.testimonials',
                  'home.showcase', 'home.process', 'home.faq', 'home.banner', 'home.journal'] as $cle) {
            $section = PageHeader::query()->where('key', $cle)->firstOrFail();

            // assertSee construirait un diff sur 200 ko de HTML en cas d'échec,
            // ce qui fige la suite. Un booléen suffit à situer le problème.
            $this->assertTrue(
                str_contains($contenu, $section->title),
                "Le titre de la section {$cle} n'apparaît pas sur la page d'accueil.",
            );
        }
    }

    public function test_the_marquee_figures_come_from_the_database(): void
    {
        PageHeader::query()->where('key', 'home.marquee')->update([
            'tags' => ['42 | Libelle de controle'],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-count="42"', false)
            ->assertSee('Libelle de controle');
    }

    public function test_the_home_page_carries_exactly_one_level_one_heading(): void
    {
        // Le bandeau porte le seul <h1> : les chiffres du bandeau défilant et
        // les cycles sont des titres de section.
        $this->assertSame(1, substr_count($this->get('/')->getContent(), '<h1'));
    }

    public function test_editing_a_home_section_updates_the_page(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $index = PageHeader::query()->orderBy('group')->orderBy('position')->orderBy('id')
            ->pluck('key')->search('home.banner');

        Livewire::test(PageHeaderManager::class)
            ->set("headers.{$index}.title", 'Un tout nouvel appel')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/')->assertOk()->assertSee('Un tout nouvel appel', false);
    }

    public function test_no_template_placeholder_phone_number_remains(): void
    {
        foreach (['/', '/contact', '/l-ecole'] as $url) {
            $contenu = $this->get($url)->getContent();

            $this->assertStringNotContainsString('1234567890', $contenu, "Numéro de gabarit encore présent sur {$url}");
            $this->assertStringNotContainsString('2125557398', $contenu, "Numéro de gabarit encore présent sur {$url}");
        }
    }
}
