<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\Social;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $socialsDefault = [
            ['key' => 'email',     'value' => 'info@example.com'],
            ['key' => 'phone',     'value' => '+123456789'],
            ['key' => 'facebook',  'value' => 'https://fb.com'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com'],
        ];

        foreach ($socialsDefault as $data) {
            Social::factory()->create($data);
        }

        $difficultyData = [
            ['Starter', 'images/difficulties/starter.png', '#026AA2'],
            ['Beginner', 'images/difficulties/beginner.png', '#175CD3'],
            ['Middle', 'images/difficulties/middle.png', '#6941C6'],
            ['High', 'images/difficulties/high.png', '#B54708'],
            ['Very High', 'images/difficulties/very-high.png', '#C11574'],
            ['Dangerously High', 'images/difficulties/dangerously-high.png', '#C01048'],
        ];

        foreach ($difficultyData as [$name, $icon, $color]) {
            Difficulty::create([
                'name'  => $name,
                'icon'  => $icon,
                'color' => $color,
            ]);
        }

        Category::factory(30)->create();

        Quiz::factory(50)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
