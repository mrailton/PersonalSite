<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentences(5, true),
            'published_at' => $this->faker->dateTimeBetween('-2 years', '-1 day'),
        ];
    }

    public function unpublished(): ArticleFactory
    {
        return $this->state(
            fn (array $attributes) => [
                'published_at' => null,
            ]
        );
    }
}
