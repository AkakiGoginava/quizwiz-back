<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function getQuizzes() {
        return Quiz::orderBy("id")
            ->with('categories:id')
            ->cursorPaginate(9)
            ->through(function ($quiz) {
            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'total_users' => $quiz->total_users,
                'categories' => $quiz->categories->pluck('id'),
                'difficulty' => $quiz->difficulty_id,
                'image' => asset($quiz->image),
            ];
        });
    }

    public function getCategories() {
        return Category::select(['id', 'name'])->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
    }

    public function getDifficulties() {
        return Difficulty::select(['id', 'name', 'icon', 'color'])->get()->map(function ($difficulty) {
            return [
                'id' => $difficulty->id,
                'name' => $difficulty->name,
                'icon' => asset($difficulty->icon),
                'color' => $difficulty->color,
            ];
        });
    }
}
