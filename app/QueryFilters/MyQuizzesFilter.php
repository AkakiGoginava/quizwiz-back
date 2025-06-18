<?php

namespace App\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;

class MyQuizzesFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $user = Auth::user();
        if ($user) {
            if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
                $query->whereHas('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } else {
                $query->whereDoesntHave('users', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }
    }
}