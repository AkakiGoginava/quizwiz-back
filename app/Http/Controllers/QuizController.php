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
        $perPage = request('per_page', 9);

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
            ->cursorPaginate($perPage)->appends(request()->query());

        return QuizResource::collection($quizzes);
    }

    public function getQuiz($id)
    {
        $quiz = Quiz::with(['categories', 'difficulty', 'questions.answers'])
            ->withCount(['points', 'questions'])
            ->findOrFail($id);

        request()->merge([
            'filter' => [
                'my_quizzes' => false,
                'categories.id' => $quiz->categories->pluck('id')->implode(','),
            ],
        ]);

        $similarQuizzes = QueryBuilder::for(Quiz::class)
            ->with(['categories', 'difficulty'])
            ->allowedFilters([
                AllowedFilter::exact('categories.id'),
                AllowedFilter::custom('my_quizzes', new MyQuizzesFilter),
            ])
            ->where('id', '!=', $quiz->id)
            ->limit(3)
            ->get();

        return (new QuizResource($quiz))->additional([
            'similar_quizzes' => QuizResource::collection($similarQuizzes),
        ]);
    }
}
