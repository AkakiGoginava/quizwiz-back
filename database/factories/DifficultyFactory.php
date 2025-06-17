<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DifficultyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'  => fake()->word(),
            'icon'  => fake()->imageUrl(64, 64, 'abstract'),
            'color' => fake()->hexColor(),
        ];
    }
}
