<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'role' => $this->faker->randomElement([
                'Directrice artistique',
                'Développeur front-end',
                'Designer produit',
                'Chef de projet',
                'Motion designer',
                'Stratège de marque',
            ]),
            'bio' => $this->faker->paragraph(4),
            'photo' => null,
            'email' => $this->faker->safeEmail(),
            'socials' => [
                'linkedin' => 'https://linkedin.com/in/'.Str::slug($name),
            ],
            'is_active' => true,
            'position' => 0,
        ];
    }
}
