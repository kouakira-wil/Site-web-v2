<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Page Contact.
 *
 * Elle portait un formulaire qui ne partait nulle part, deux blocs de
 * coordonnées identiques, cinq réseaux dont quatre menaient à la page
 * elle-même, et sept images absentes du dépôt.
 */
class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    private function page(): string
    {
        return $this->get('/contact')->assertOk()->getContent();
    }

    public function test_the_form_posts_to_the_route_that_handles_it(): void
    {
        $contenu = $this->page();

        // Il visait « # » : le message n'allait nulle part, alors que la route
        // existe, valide, range le message et prévient l'équipe.
        $this->assertMatchesRegularExpression(
            '#<form[^>]*action="[^"]*/contact"[^>]*method="post"#',
            $contenu,
        );
        $this->assertStringContainsString('name="_token"', $contenu);
    }

    public function test_a_visitor_can_write_from_the_page_itself(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Awa Traoré',
            'email' => 'awa@example.test',
            'subject' => 'Demande de préinscription',
            'message' => 'Je souhaite connaître les conditions d’accès au cycle Licence.',
            'reference_interne' => '',
        ])->assertRedirect('/contact')->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', ['email' => 'awa@example.test']);
    }

    public function test_the_confirmation_is_shown_after_sending(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Awa Traoré',
            'email' => 'awa@example.test',
            'message' => 'Je souhaite connaître les conditions d’accès au cycle Licence.',
            'reference_interne' => '',
        ]);

        $this->assertStringContainsString('votre message est bien arrivé', $this->page());
    }

    public function test_a_rejected_field_keeps_its_value_and_says_why(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'Awa Traoré',
            'email' => 'pas-une-adresse',
            'message' => 'Trop court.',
            'reference_interne' => '',
        ])->assertRedirect('/contact');

        $contenu = $this->page();

        // Le visiteur retrouve ce qu'il a tapé, et lit la raison sous le champ.
        $this->assertStringContainsString('value="Awa Traoré"', $contenu);
        $this->assertStringContainsString('contact-erreur', $contenu);
        $this->assertSame(0, ContactMessage::query()->count());
    }

    public function test_the_two_detail_blocks_no_longer_repeat_each_other(): void
    {
        // Seuls les deux encarts de coordonnées, en haut : le lien WhatsApp
        // plus bas porte lui aussi le numéro, dans son libellé.
        $contenu = $this->bloc();
        $contenu = substr($contenu, 0, (int) strpos($contenu, 'sec-4-about-form'));

        /*
         * Ils affichaient mot pour mot la même adresse, le même téléphone et
         * le même e-mail. Le numéro lisible ne doit plus paraître qu'une fois
         * (l'adresse e-mail, elle, figure deux fois par nature : dans le
         * `mailto:` et dans le texte du lien).
         */
        $this->assertSame(1, substr_count($contenu, config('ecole.telephone')));
        $this->assertStringContainsString('Horaires d’accueil', $contenu);
    }

    public function test_only_the_networks_the_school_actually_has_are_linked(): void
    {
        $bloc = $this->bloc();

        $this->assertStringContainsString(config('social.facebook'), $bloc);
        $this->assertStringContainsString(config('social.tiktok'), $bloc);

        // Twitter, LinkedIn et YouTube menaient vers /contact, c'est-à-dire la
        // page où le visiteur se trouve déjà.
        foreach (['Twitter', 'Linkedin', 'Youtube'] as $absent) {
            $this->assertStringNotContainsString($absent, $bloc);
        }
    }

    public function test_an_unset_network_is_not_linked_to_the_contact_page(): void
    {
        config(['social.instagram' => '']);

        $this->assertStringNotContainsString('Instagram', $this->bloc());

        config(['social.instagram' => 'https://www.instagram.com/escat/']);

        $this->assertStringContainsString('https://www.instagram.com/escat/', $this->bloc());
    }

    public function test_every_image_of_the_page_exists_on_disk(): void
    {
        /*
         * La bande du bas visait img-130 à img-136 en .webp, aucun de ces
         * fichiers n'étant dans le dépôt : sept icônes cassées à la suite.
         */
        preg_match_all('#/assets/imgs/([^"]+)#', $this->bloc(), $images);

        $absents = array_values(array_filter(
            array_unique($images[1]),
            fn (string $chemin): bool => ! file_exists(public_path('assets/imgs/'.$chemin)),
        ));

        $this->assertSame([], $absents);
    }

    public function test_the_fields_speak_french(): void
    {
        $this->assertStringNotContainsString('Your ', $this->bloc());
    }

    /**
     * Le corps de la page, sans l'en-tête ni le pied commun aux autres pages.
     */
    private function bloc(): string
    {
        $contenu = $this->page();
        $contenu = substr($contenu, (int) strpos($contenu, 'sec-1-contact'));

        return substr($contenu, 0, (int) strpos($contenu, 'at-footer'));
    }
}
