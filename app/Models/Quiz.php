<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function points()
    {
        return $this->hasManyThrough(
            Answer::class,
            Question::class,
            'quiz_id',
            'question_id',
            'id',
            'id'
        )->where('is_correct', true);
    }
}
