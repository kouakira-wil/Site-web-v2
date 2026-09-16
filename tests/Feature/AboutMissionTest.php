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

class AboutMissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\AboutIntroSeeder::class);
    }

    private function mission(): PageHeader
    {
        return PageHeader::query()->where('key', 'ecole.mission')->firstOrFail();
    }

    public function test_each_mission_gets_its_own_check(): void
    {
        $this->mission()->update([
            'intro' => 'Comment nous procedons.',
            'tags' => ['Premiere mission', 'Deuxieme mission', 'Troisieme mission'],
        ]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('mission-split', $contenu);
        $this->assertSame(3, substr_count($contenu, 'mission-split__check'));
        $this->assertStringContainsString('Deuxieme mission', $contenu);
        $this->assertStringContainsString('Comment nous procedons.', $contenu);
    }

    public function test_the_mission_is_no_longer_repeated_in_the_identity_grid(): void
    {
        $this->mission()->update(['title' => 'Notre mission', 'tags' => ['Une mission']]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        // Elle a sa propre section : elle ne doit pas aussi figurer dans la grille.
        $this->assertSame(1, substr_count($contenu, 'Notre mission'));
    }

    public function test_an_empty_mission_renders_nothing(): void
    {
        $this->mission()->update(['intro' => null, 'tags' => []]);

        $this->get('/l-ecole')->assertOk()->assertDontSee('mission-split');
    }

    public function test_the_photo_slot_is_offered_in_the_back_office(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->assertContains('image', PageHeaderSchema::fieldsFor($this->mission()));

        Livewire::test(PageHeaderForm::class, ['header' => $this->mission()])
            ->assertSee('Missions')
            ->assertSee('Photo')
            ->set('tags', "Une mission\\nUne autre")
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()->assertSee('Une autre');
    }

    public function test_the_section_follows_the_metrics_strip(): void
    {
        // Le seeder laisse la mission vide : sans contenu, la section ne sort pas.
        $this->mission()->update(['tags' => ['Une mission']]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertLessThan(
            strpos($contenu, 'mission-split'),
            strpos($contenu, 'reperes-grid'),
        );
    }
}
