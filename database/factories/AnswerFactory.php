<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(6),
            'is_correct' => fake()->boolean(),
        ];
    }
}
