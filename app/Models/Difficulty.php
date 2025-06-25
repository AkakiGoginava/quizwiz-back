<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Difficulty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'color'];

    public function getIconAttribute($value)
    {
        return asset($value);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
