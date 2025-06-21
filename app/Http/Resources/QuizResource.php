<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'total_users' => $this->total_users,
            'categories'  => $this->categories->map(function ($category) {
                return [
                    'id'   => $category->id,
                    'name' => $category->name,
                ];
            }),
            'difficulty' => [
                'id'    => $this->difficulty->id,
                'name'  => $this->difficulty->name,
                'icon'  => $this->difficulty->icon,
                'color' => $this->difficulty->color,
            ],
            'image'          => $this->image,
            'created_at'     => $this->created_at,
            'instructions'   => $this->instructions,
            'description'    => $this->description,
            'question_count' => $this->questions_count,
            'points'         => $this->points_count,
            'max_time'       => $this->max_time,

            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($question) {
                    return [
                        'id'                   => $question->id,
                        'description'          => $question->description,
                        'correct_answer_count' => $question->answers->where('is_correct', true)->count(),
                        'answers'              => $question->answers->map(function ($answer) {
                            return [
                                'id'          => $answer->id,
                                'description' => $answer->description,
                            ];
                        }),
                    ];
                });

            }),
        ];
    }
}
