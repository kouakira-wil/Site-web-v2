<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Admin\Pages\PageHeaderForm;
use App\Models\PageHeader;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PageHeaderTest extends TestCase
{
    use RefreshDatabase;

    private function seedHeaders(): void
    {
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
    }

    public function test_every_public_page_renders_its_header(): void
    {
        $this->seedHeaders();

        foreach (['about' => '/l-ecole', 'equipe' => '/equipe', 'journal' => '/journal', 'contact' => '/contact', 'filieres' => '/filieres'] as $cle => $url) {
            $header = PageHeader::query()->where('key', $cle)->firstOrFail();

            $reponse = $this->get($url);
            $reponse->assertOk();
            $reponse->assertSee($header->title, false);
            $reponse->assertSee('sd3-hero__stage');
        }
    }

    public function test_a_page_carries_exactly_one_level_one_heading(): void
    {
        $this->seedHeaders();

        foreach (['/l-ecole', '/equipe', '/journal', '/contact', '/filieres'] as $url) {
            $contenu = $this->get($url)->getContent();

            $this->assertSame(1, substr_count($contenu, '<h1'), "Un seul <h1> attendu sur {$url}");
        }
    }

    public function test_staff_can_change_a_header_and_the_site_follows(): void
    {
        $this->seedHeaders();
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $bloc = PageHeader::query()->where('key', 'contact')->firstOrFail();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc])
            ->set('title', 'Un tout nouveau titre')
            ->set('intro', 'Une introduction ajoutée depuis le back-office.')
            ->call('save')
            ->assertHasNoErrors();

        $reponse = $this->get('/contact');
        $reponse->assertSee('Un tout nouveau titre', false);
        $reponse->assertSee('Une introduction ajoutée depuis le back-office.', false);
    }

    public function test_the_hero_renders_its_second_line_tags_and_button(): void
    {
        $this->seedHeaders();
        $header = PageHeader::query()->where('key', 'about')->firstOrFail();
        $header->update([
            'title' => 'Premiere ligne',
            'title_accent' => 'Seconde ligne',
            'cta_label' => 'Nous ecrire',
            'cta_url' => '/contact',
            'tags' => ['Brazzaville', 'Gestion'],
        ]);

        $reponse = $this->get('/l-ecole');
        $reponse->assertOk();
        $reponse->assertSee('Premiere ligne');
        $reponse->assertSee('Seconde ligne');
        $reponse->assertSee('sd3-hero__line--accent');
        $reponse->assertSee('Nous ecrire');
        $reponse->assertSee('Brazzaville');

        // Les variables du hero sont portees par .sd3-page : sans cette classe
        // le fond serait transparent.
        $reponse->assertSee('sd3-page');
    }

    public function test_a_header_without_button_or_tags_omits_them(): void
    {
        $this->seedHeaders();
        PageHeader::query()->where('key', 'equipe')->update([
            'cta_label' => null,
            'tags' => [],
            'title_accent' => null,
        ]);

        $contenu = $this->get('/equipe')->assertOk()->getContent();

        $this->assertStringNotContainsString('sd3-hero__actions', $contenu);
        $this->assertStringNotContainsString('data-sd3-tags', $contenu);
        $this->assertStringNotContainsString('sd3-hero__line--accent', $contenu);
    }

    public function test_removing_an_image_never_deletes_a_file_outside_its_folder(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $this->seedHeaders();
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        // Fichier d'un tiers, hors du dossier des visuels d'en-tête.
        \Illuminate\Support\Facades\Storage::disk('public')->put('actualites/photo-d-autrui.webp', 'contenu');

        $bloc = PageHeader::query()->orderBy('id')->firstOrFail();
        $bloc->forceFill(['image' => 'actualites/photo-d-autrui.webp'])->save();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc->fresh()])
            ->call('removeImage')
            ->call('save')
            ->assertHasNoErrors();

        // La référence est retirée du bloc, mais le fichier reste intact.
        $this->assertNull($bloc->fresh()->image);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists('actualites/photo-d-autrui.webp');
    }

    public function test_the_form_cannot_carry_a_stored_path(): void
    {
        $this->seedHeaders();
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $bloc = PageHeader::query()->orderBy('id')->firstOrFail();

        // `image` est typée : elle n'accepte qu'un fichier téléversé. Un chemin
        // ne peut donc pas transiter par le formulaire, même altéré côté client.
        $this->expectException(\TypeError::class);

        Livewire::test(PageHeaderForm::class, ['header' => $bloc])
            ->set('image', 'actualites/photo-d-autrui.webp');
    }

    public function test_a_header_title_is_required(): void
    {
        $this->seedHeaders();
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $bloc = PageHeader::query()->orderBy('id')->firstOrFail();

        Livewire::test(PageHeaderForm::class, ['header' => $bloc])
            ->set('title', '')
            ->call('save')
            ->assertHasErrors('title');
    }
}
