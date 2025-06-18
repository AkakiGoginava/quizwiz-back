<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\QueryFilters\MyQuizzesFilter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuizController extends Controller
{
    public function getQuizzes()
    {
        $quizzes = QueryBuilder::for(Quiz::class)
            ->with(['categories', 'difficulty', 'questions'])
            ->withCount('points')
            ->allowedFilters([
                AllowedFilter::exact('categories.id'),
                'title',
                'difficulty_id',
                AllowedFilter::custom('my_quizzes', new MyQuizzesFilter),
            ])
            ->allowedSorts(['title', 'created_at', 'total_users', 'id'])
            ->cursorPaginate(9)->appends(request()->query());

        return QuizResource::collection($quizzes);
    }
}
