<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = Str::ucfirst($this->faker->unique()->words(3, true));

        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'client' => $this->faker->company(),
            'year' => $this->faker->numberBetween(2021, 2026),
            'external_url' => $this->faker->optional()->url(),
            'excerpt' => $this->faker->sentence(14),
            'description' => implode("\n\n", $this->faker->paragraphs(4)),
            'cover_image' => null,
            'status' => Project::STATUS_PUBLISHED,
            'is_featured' => false,
            'position' => 0,
            'published_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => Project::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }
}
