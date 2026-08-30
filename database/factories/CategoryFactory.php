<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => Str::ucfirst($name),
            'slug' => Str::slug($name),
            'type' => Category::TYPE_PROJECT,
            'description' => $this->faker->optional()->sentence(),
            'position' => 0,
        ];
    }

    public function forPosts(): static
    {
        return $this->state(fn (): array => ['type' => Category::TYPE_POST]);
    }
}
