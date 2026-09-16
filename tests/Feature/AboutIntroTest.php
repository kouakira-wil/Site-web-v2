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

class AboutIntroTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
        $this->seed(\Database\Seeders\AboutIntroSeeder::class);
    }

    private function bloc(string $cle): PageHeader
    {
        return PageHeader::query()->where('key', $cle)->firstOrFail();
    }

    public function test_the_section_opens_the_school_page(): void
    {
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringContainsString('presentation-grid', $contenu);
        $this->assertStringContainsString($this->bloc('ecole.presentation')->title, $contenu);

        // Elle précède vision, mission, objectif et valeurs.
        $this->assertLessThan(
            strpos($contenu, 'titre-identite') ?: PHP_INT_MAX,
            strpos($contenu, 'presentation-grid'),
        );
    }

    public function test_both_arguments_and_their_icons_are_rendered(): void
    {
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertSame(2, substr_count($contenu, 'presentation-args__item'));
        $this->assertStringContainsString($this->bloc('ecole.presentation.1')->title, $contenu);
        $this->assertStringContainsString($this->bloc('ecole.presentation.2')->title, $contenu);
        $this->assertSame(2, substr_count($contenu, 'presentation-args__icon'));
    }

    public function test_the_key_points_come_from_the_tags(): void
    {
        $this->bloc('ecole.presentation')->update(['tags' => ['Un point', 'Un autre point']]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertSame(2, substr_count($contenu, 'presentation-points__item'));
        $this->assertStringContainsString('Un autre point', $contenu);
    }

    public function test_empty_optional_parts_are_omitted(): void
    {
        $this->bloc('ecole.presentation')->update(['cta_label' => null, 'tags' => [], 'intro' => null]);

        $contenu = $this->get('/l-ecole')->assertOk()->getContent();

        $this->assertStringNotContainsString('presentation-body__actions', $contenu);
        $this->assertStringNotContainsString('presentation-points', $contenu);
        $this->assertStringNotContainsString('presentation-body__lead', $contenu);
    }

    public function test_the_section_is_editable_from_the_back_office(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('ecole.presentation')])
            ->assertSee('Points clés')
            ->assertSee('Photo')
            ->set('title', 'Un titre tout neuf')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/l-ecole')->assertOk()->assertSee('Un titre tout neuf');
    }

    public function test_both_photo_slots_are_offered_in_the_back_office(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('ecole.presentation')])
            ->assertSee('Photo principale')
            ->assertSee('Photo superposée');
    }

    public function test_each_slot_stores_its_own_file(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        Livewire::test(PageHeaderForm::class, ['header' => $this->bloc('ecole.presentation')])
            ->set('image', \Illuminate\Http\UploadedFile::fake()->image('grande.jpg'))
            ->set('image_secondary', \Illuminate\Http\UploadedFile::fake()->image('petite.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $bloc = $this->bloc('ecole.presentation');

        $this->assertNotNull($bloc->image);
        $this->assertNotNull($bloc->image_secondary);
        $this->assertNotSame($bloc->image, $bloc->image_secondary);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($bloc->image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($bloc->image_secondary);

        // Les deux photos apparaissent sur la page.
        $contenu = $this->get('/l-ecole')->assertOk()->getContent();
        $this->assertStringContainsString('presentation-media__main', $contenu);
        $this->assertStringContainsString('presentation-media__inset', $contenu);
    }

    public function test_removing_one_slot_leaves_the_other_untouched(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $bloc = $this->bloc('ecole.presentation');
        $bloc->forceFill([
            'image' => 'entetes/grande.webp',
            'image_secondary' => 'entetes/petite.webp',
        ])->save();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc->fresh()])
            ->call('removeImage', 'image_secondary')
            ->call('save')
            ->assertHasNoErrors();

        $apres = $this->bloc('ecole.presentation');

        $this->assertSame('entetes/grande.webp', $apres->image);
        $this->assertNull($apres->image_secondary);
    }

    public function test_an_argument_exposes_its_icon_only(): void
    {
        $champs = PageHeaderSchema::fieldsFor($this->bloc('ecole.presentation.1'));

        $this->assertSame(['title', 'intro', 'icon', 'label'], $champs);
        // Le bloc parent, lui, porte la photo et le bouton.
        $this->assertContains('image', PageHeaderSchema::fieldsFor($this->bloc('ecole.presentation')));
    }
}
