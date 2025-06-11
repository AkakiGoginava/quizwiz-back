<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckUniqueRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class ValidationController extends Controller
{
    public function checkUnique(CheckUniqueRequest $request): JsonResponse
    {
        $attributes = $request->validated();

        $exists = User::where($attributes['field'], $attributes['value'])->exists();

        return response()->json(['unique' => ! $exists]);
    }
}
