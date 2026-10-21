<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Notification de l'équipe à la réception d'un message.
 *
 * Elle ne partait jamais : `MAIL_CONTACT_RECIPIENT` n'était pas renseigné, le
 * garde-fou rendait la main sans rien dire, et l'envoi est de toute façon
 * enveloppé dans un `try` pour qu'une panne SMTP ne fasse pas échouer le
 * visiteur. Trois raisons de ne rien voir, d'où ces tests.
 */
class ContactMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function demande(array $champs = []): array
    {
        return $champs + [
            'name' => 'Awa Traoré',
            'email' => 'awa@example.test',
            'subject' => 'Demande de préinscription',
            'message' => 'Je souhaite connaître les conditions d’accès au cycle Licence.',
            'website' => '',
        ];
    }

    public function test_a_recipient_is_configured_out_of_the_box(): void
    {
        // Sans valeur par défaut, une installation neuve enregistrait les
        // messages sans jamais prévenir personne.
        $this->assertNotEmpty(config('mail.contact_recipient'));
    }

    public function test_the_team_is_notified_at_the_configured_address(): void
    {
        Mail::fake();

        $this->post('/contact', $this->demande())->assertSessionHas('status');

        Mail::assertSent(
            ContactMessageReceived::class,
            fn (ContactMessageReceived $courriel): bool => $courriel->hasTo(config('mail.contact_recipient')),
        );
    }

    public function test_the_reply_goes_back_to_the_visitor(): void
    {
        Mail::fake();

        $this->post('/contact', $this->demande());

        Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $courriel): bool {
            // Répondre depuis la boîte de l'école doit écrire au visiteur, pas
            // à l'adresse d'envoi du site.
            return $courriel->hasReplyTo('awa@example.test');
        });
    }

    public function test_the_mail_renders_without_throwing(): void
    {
        /*
         * Le rendu touche `route('admin.messages.index')` : une route renommée
         * ferait lever le gabarit, et l'exception serait avalée par le `try`
         * du contrôleur — message en base, personne averti.
         */
        $message = ContactMessage::query()->create($this->demande(['website' => null]) + ['website' => null]);

        $html = (new ContactMessageReceived($message))->render();

        $this->assertStringContainsString('Awa Traoré', $html);
        $this->assertStringContainsString('Demande de préinscription', $html);
        $this->assertStringContainsString(route('admin.messages.index'), $html);
    }

    public function test_a_missing_recipient_is_written_down_instead_of_passing_unnoticed(): void
    {
        config(['mail.contact_recipient' => null]);

        Mail::fake();
        Log::spy();

        $this->post('/contact', $this->demande())->assertSessionHas('status');

        Mail::assertNothingSent();
        Log::shouldHaveReceived('warning')->once();

        // Le visiteur, lui, ne doit rien subir de ce défaut de configuration.
        $this->assertDatabaseHas('contact_messages', ['email' => 'awa@example.test']);
    }

    public function test_the_command_refuses_to_run_without_a_recipient(): void
    {
        config(['mail.contact_recipient' => null]);

        $this->artisan('contact:tester')->assertFailed();
    }

    public function test_the_command_sends_a_sample_message(): void
    {
        Mail::fake();

        $this->artisan('contact:tester', ['--destinataire' => 'verification@example.test'])
            ->assertSuccessful();

        Mail::assertSent(
            ContactMessageReceived::class,
            fn (ContactMessageReceived $courriel): bool => $courriel->hasTo('verification@example.test'),
        );
    }
}
