<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Difficulty;

class InfoController extends Controller
{
    public function getDifficulties()
    {
        return Difficulty::select(['id', 'name', 'icon', 'color'])->get();

    }

    public function getCategories()
    {
        return Category::select(['id', 'name'])->get();
    }
}
