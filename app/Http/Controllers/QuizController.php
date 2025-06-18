<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\QueryFilters\MyQuizzesFilter;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuizController extends Controller
{
    public function getQuizzes(): AnonymousResourceCollection
    {
        $quizzes = QueryBuilder::for(Quiz::class)
            ->with(['categories', 'difficulty'])
            ->withCount(['points', 'questions'])
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

    public function getQuiz($id)
    {
        $quiz = Quiz::with(['categories', 'difficulty', 'questions.answers'])
            ->withCount('points')
            ->findOrFail($id);

        return new QuizResource($quiz);
    }
}
