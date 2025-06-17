<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QuizController extends Controller
{
    public function getQuizzes()
    {
        return QueryBuilder::for(Quiz::class)
            ->allowedIncludes(['categories', 'difficulty'])
            ->allowedFilters([
                AllowedFilter::exact('categories.id'), 
                'title', 
                'difficulty_id'
            ])
            ->allowedSorts(['title', 'created_at', 'total_users', 'id'])
            ->cursorPaginate(9)
            ->through(function ($quiz) {
                return [
                    'id'          => $quiz->id,
                    'title'       => $quiz->title,
                    'total_users' => $quiz->total_users,
                    'categories'  => $quiz->categories->map(function ($category) {
                        return [
                            'id'   => $category->id,
                            'name' => $category->name,
                        ];
                    }),
                    'difficulty' => [
                        'id'   => $quiz->difficulty->id,
                        'name' => $quiz->difficulty->name,
                        'icon' => asset($quiz->difficulty->icon),
                    ],
                    'image' => asset($quiz->image),
                    'created_at' => $quiz->created_at,
                ];
            })
            ->appends(request()->query());
    }

}
