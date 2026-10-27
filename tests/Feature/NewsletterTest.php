<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\PageHeader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PageHeaderSeeder::class);
        $this->seed(\Database\Seeders\SchoolIdentitySeeder::class);
    }

    public function test_the_form_is_present_on_the_school_page(): void
    {
        $this->get('/l-ecole')
            ->assertOk()
            ->assertSee('newsletter-box')
            ->assertSee('action="'.route('newsletter.store').'"', false);
    }

    public function test_a_visitor_can_subscribe(): void
    {
        $this->from('/l-ecole')
            ->post(route('newsletter.store'), ['email' => 'Etudiant@Exemple.COM'])
            ->assertRedirect()
            ->assertSessionHas('newsletter_status');

        // L'adresse est normalisée en minuscules pour éviter les doublons.
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'etudiant@exemple.com']);
    }

    public function test_subscribing_twice_does_not_duplicate_the_address(): void
    {
        foreach ([1, 2] as $ignore) {
            $this->from('/l-ecole')->post(route('newsletter.store'), ['email' => 'double@exemple.com']);
        }

        $this->assertSame(1, NewsletterSubscriber::query()->where('email', 'double@exemple.com')->count());
    }

    public function test_an_unsubscribed_address_is_reactivated(): void
    {
        NewsletterSubscriber::query()->create([
            'email' => 'revenu@exemple.com',
            'unsubscribed_at' => now()->subMonth(),
        ]);

        $this->from('/l-ecole')->post(route('newsletter.store'), ['email' => 'revenu@exemple.com']);

        $this->assertNull(NewsletterSubscriber::query()->where('email', 'revenu@exemple.com')->first()->unsubscribed_at);
    }

    public function test_an_invalid_address_is_rejected(): void
    {
        $this->from('/l-ecole')
            ->post(route('newsletter.store'), ['email' => 'pas-une-adresse'])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, NewsletterSubscriber::query()->count());
    }

    public function test_the_honeypot_blocks_a_bot(): void
    {
        // Seul un robot remplit le champ masqué.
        $this->from('/l-ecole')
            ->post(route('newsletter.store'), [
                'email' => 'robot@exemple.com',
                'reference_interne' => 'http://spam.example',
            ])
            ->assertSessionHasErrors('reference_interne');

        $this->assertSame(0, NewsletterSubscriber::query()->count());
    }

    public function test_the_identity_section_stays_hidden_while_empty(): void
    {
        // Vision, mission, objectif et valeurs sont semés vides : la section
        // ne doit pas afficher quatre titres suivis de rien.
        $this->get('/l-ecole')->assertOk()->assertDontSee('titre-identite');
    }

    public function test_the_identity_section_appears_once_filled(): void
    {
        PageHeader::query()->where('key', 'ecole.vision')->update([
            'intro' => 'Former les cadres dont le pays a besoin.',
        ]);
        PageHeader::query()->where('key', 'ecole.valeurs')->update([
            'tags' => ['Rigueur', 'Proximité'],
        ]);

        $reponse = $this->get('/l-ecole');
        $reponse->assertOk();
        $reponse->assertSee('titre-identite');
        $reponse->assertSee('Former les cadres dont le pays a besoin.');
        $reponse->assertSee('Rigueur');

        // Les deux blocs encore vides restent masqués.
        $reponse->assertDontSee('Notre mission');
    }
}
