<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'subject' => $this->faker->randomElement([
                'Demande de devis',
                'Refonte de site',
                'Identité visuelle',
                'Partenariat',
            ]),
            'message' => $this->faker->paragraph(5),
            'status' => ContactMessage::STATUS_NEW,
            'ip_address' => $this->faker->ipv4(),
            'read_at' => null,
        ];
    }
}
