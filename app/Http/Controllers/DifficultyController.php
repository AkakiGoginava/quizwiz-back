<?php

namespace App\Http\Controllers;

use App\Models\Difficulty;
use Illuminate\Http\Request;

class DifficultyController extends Controller
{
    
    public function getDifficulties()
    {
        return Difficulty::select(['id', 'name', 'icon', 'color'])->get()->map(function ($difficulty) {
            return [
                'id'    => $difficulty->id,
                'name'  => $difficulty->name,
                'icon'  => asset($difficulty->icon),
                'color' => $difficulty->color,
            ];
        });
    }
}
