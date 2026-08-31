<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Testimonials\TestimonialForm;
use App\Livewire\Admin\Testimonials\TestimonialIndex;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsStaff(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
    }

    public function test_staff_can_create_a_testimonial_with_a_photo(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Livewire::test(TestimonialForm::class)
            ->set('quote', 'Une formation qui prepare vraiment au terrain.')
            ->set('program', 'Genie Informatique et Reseaux')
            ->set('graduation_year', '2023')
            ->set('location', 'Brazzaville, Congo')
            ->set('photo', UploadedFile::fake()->image('diplomee.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $temoignage = Testimonial::query()->firstOrFail();

        $this->assertSame(2023, $temoignage->graduation_year);
        $this->assertSame('Genie Informatique et Reseaux', $temoignage->program);
        Storage::disk('public')->assertExists($temoignage->photo);
    }

    public function test_an_anonymous_testimonial_is_signed_by_its_promotion(): void
    {
        $temoignage = Testimonial::query()->create([
            'quote' => 'Sans nom.',
            'graduation_year' => 2022,
        ]);

        $this->assertSame('Diplômé(e), promotion 2022', $temoignage->signature());
    }

    public function test_the_quote_is_wrapped_once_in_french_quotation_marks(): void
    {
        $sans = Testimonial::query()->create(['quote' => 'Texte simple']);
        $avec = Testimonial::query()->create(['quote' => '« Deja entoure »']);

        $this->assertSame('« Texte simple »', $sans->formattedQuote());
        // Un rédacteur qui tape lui-même les guillemets n'en obtient pas deux paires.
        $this->assertSame('« Deja entoure »', $avec->formattedQuote());
    }

    public function test_the_quote_is_required(): void
    {
        $this->actingAsStaff();

        Livewire::test(TestimonialForm::class)
            ->set('quote', '')
            ->call('save')
            ->assertHasErrors('quote');
    }

    public function test_a_hidden_testimonial_does_not_reach_the_home_page(): void
    {
        Testimonial::query()->create(['quote' => 'Visible sur le site', 'is_active' => true]);
        Testimonial::query()->create(['quote' => 'Masque du public', 'is_active' => false]);

        $reponse = $this->get('/');
        $reponse->assertOk();
        $reponse->assertSee('Visible sur le site');
        $reponse->assertDontSee('Masque du public');
    }

    public function test_deleting_a_testimonial_removes_its_photo(): void
    {
        Storage::fake('public');
        $this->actingAsStaff();

        Storage::disk('public')->put('temoignages/photo.webp', 'contenu');
        $temoignage = Testimonial::query()->create([
            'quote' => 'A supprimer.',
            'photo' => 'temoignages/photo.webp',
        ]);

        Livewire::test(TestimonialIndex::class)->call('delete', $temoignage->id);

        $this->assertDatabaseMissing('testimonials', ['id' => $temoignage->id]);
        Storage::disk('public')->assertMissing('temoignages/photo.webp');
    }

    public function test_the_carousel_repeats_a_short_list_so_it_can_loop(): void
    {
        // Swiper a besoin d'assez de vignettes : sous quatre, la liste est doublée.
        Testimonial::query()->create(['quote' => 'Unique temoignage', 'is_active' => true]);

        $contenu = $this->get('/')->getContent();

        $this->assertSame(2, substr_count($contenu, 'Unique temoignage'));
    }
}
