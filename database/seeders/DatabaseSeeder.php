<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Difficulty::factory(6)->create();

        Category::factory(30)->create();

        Quiz::factory(30)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
