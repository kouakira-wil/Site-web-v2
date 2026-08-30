<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = Str::ucfirst($this->faker->unique()->words(5, true));

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory()->forPosts(),
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->sentence(18),
            'body' => implode("\n\n", $this->faker->paragraphs(7)),
            'cover_image' => null,
            'status' => Post::STATUS_PUBLISHED,
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => Post::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }
}
