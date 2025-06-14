<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DifficultyFactory extends Factory
{
    public function definition(): array
    {
        $names = ['Starter', 'Beginner', 'Middle', 'High', 'Very High', 'Dangerously High'];
        $name = fake()->unique()->randomElement($names);

        $icons = [
            'Starter'          => 'images/difficulties/starter.png',
            'Beginner'         => 'images/difficulties/beginner.png',
            'Middle'           => 'images/difficulties/middle.png',
            'High'             => 'images/difficulties/high.png',
            'Very High'        => 'images/difficulties/very-high.png',
            'Dangerously High' => 'images/difficulties/dangerously-high.png',
        ];

        $colors = [
            'Starter'          => '#026AA2',
            'Beginner'         => '#175CD3',
            'Middle'           => '#6941C6',
            'High'             => '#B54708',
            'Very High'        => '#C11574',
            'Dangerously High' => '#C01048',
        ];

        return [
            'name'  => $name,
            'icon'  => $icons[$name],
            'color' => $colors[$name],
        ];
    }
}
