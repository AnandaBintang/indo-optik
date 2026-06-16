<?php

namespace Database\Factories;

use App\Models\BlogArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogArticle>
 */
class BlogArticleFactory extends Factory
{
    protected $model = BlogArticle::class;

    public function definition(): array
    {
        $title = fake()->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 999),
            'excerpt' => fake()->sentence(14),
            'content' => fake()->paragraphs(4, true),
            'cover_image' => null,
            'status' => BlogArticle::STATUS_DRAFT,
            'published_at' => null,
            'meta_title' => $title,
            'meta_description' => fake()->sentence(16),
        ];
    }
}
