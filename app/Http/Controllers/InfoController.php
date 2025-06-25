<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Social;

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

    public function getSocials()
    {
        return Social::all();
    }
}
