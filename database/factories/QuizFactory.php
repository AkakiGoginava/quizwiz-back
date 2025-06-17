<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        $images = File::files(public_path('images/quizzes'));

        $image = 'images/quizzes/' . $images[array_rand($images)]->getFilename();

        return [
            'title'         => fake()->sentence(3, true),
            'total_users'   => fake()->numberBetween(0, 300),
            'difficulty_id' => Difficulty::query()->inRandomOrder()->value('id') ?? Difficulty::factory()->create()->id,
            'image'         => $image,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($quiz) {
            if (Category::count() === 0) {
                Category::factory()->count(3)->create();
            }

            $categoryIds = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');

            $quiz->categories()->attach($categoryIds);
        });
    }
}
