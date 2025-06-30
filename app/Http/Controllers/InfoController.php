<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Quiz;
use App\Models\Social;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class InfoController extends Controller
{
    public function getDifficulties(): Collection
    {
        return Difficulty::select(['id', 'name', 'icon', 'color'])->get();

    }

    public function getCategories(): Collection
    {
        return Category::select(['id', 'name'])->get();
    }

    public function getSocials(): Collection
    {
        return Social::all();
    }

    public function getLandingInfo(): JsonResponse
    {
        return response()->json([
            'quizzes_count'  => round(Quiz::count() / 5) * 5,
            'category_count' => round(Category::count() / 5) * 5,
        ]);
    }
}
