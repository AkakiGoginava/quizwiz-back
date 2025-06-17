<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuizController extends Controller
{
    public function getQuizzes()
    {
        $quizzes = QueryBuilder::for(Quiz::class)
            ->allowedIncludes(['categories', 'difficulty'])
            ->allowedFilters([
                AllowedFilter::exact('categories.id'),
                'title',
                'difficulty_id',
            ])
            ->allowedSorts(['title', 'created_at', 'total_users', 'id'])
            ->cursorPaginate(9)
            ->appends(request()->query());

        return QuizResource::collection(($quizzes));
    }
}
