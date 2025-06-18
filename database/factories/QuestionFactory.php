<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(10),
            'quiz_id' => Quiz::inRandomOrder()->value('id') ?? Quiz::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating((function($question) {
        Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        
        Answer::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        Answer::factory()->count(rand(0, 3))->create([
            'question_id' => $question->id,
        ]);
        }));
    }
}
