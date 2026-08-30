<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Awa Traoré',
            'email' => 'awa@example.com',
            'phone' => '+225 01 02 03 04',
            'subject' => 'Refonte de notre site',
            'message' => 'Nous souhaitons refondre notre site vitrine avant la fin du trimestre.',
        ], $overrides);
    }

    public function test_a_visitor_can_submit_the_contact_form(): void
    {
        Mail::fake();
        config(['mail.contact_recipient' => 'studio@example.com']);

        $response = $this->post(route('contact.store'), $this->validPayload());

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'awa@example.com',
            'status' => ContactMessage::STATUS_NEW,
        ]);

        Mail::assertSent(ContactMessageReceived::class);
    }

    public function test_the_honeypot_field_blocks_the_submission(): void
    {
        $response = $this->post(route('contact.store'), $this->validPayload([
            'website' => 'https://spam.example',
        ]));

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_a_short_message_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), $this->validPayload([
            'message' => 'Trop court',
        ]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_an_invalid_email_is_rejected(): void
    {
        $response = $this->post(route('contact.store'), $this->validPayload([
            'email' => 'pas-un-email',
        ]));

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_the_message_is_stored_even_when_mail_delivery_fails(): void
    {
        config(['mail.contact_recipient' => 'studio@example.com']);

        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP indisponible'));

        $response = $this->post(route('contact.store'), $this->validPayload());

        $response->assertRedirect(route('contact'));
        $this->assertDatabaseCount('contact_messages', 1);
    }
}
