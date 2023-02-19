<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'balance' => random_int(0, 1000000),
            'paid_to_date' => random_int(0, 1000000),
        ];
    }
}
