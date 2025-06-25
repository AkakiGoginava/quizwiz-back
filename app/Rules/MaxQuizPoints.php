<?php

namespace App\Rules;

use App\Models\Quiz;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxQuizPoints implements ValidationRule
{
    protected $quizId;

    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $quiz = Quiz::find($this->quizId);
        if (! $quiz) {
            return;
        }

        $maxPoints = $quiz->questions()
            ->withCount(['answers as correct_count' => function ($query) {
                $query->where('is_correct', true);
            }])
            ->get()
            ->sum('correct_count');

        if ($value > $maxPoints) {
            $fail("Points cannot be greater than the total correct answers ($maxPoints) for this quiz.");
        }
    }
}
