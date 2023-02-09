<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'issued_by' => $this->faker->company(),
            'issued_on' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'expires_on' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}
