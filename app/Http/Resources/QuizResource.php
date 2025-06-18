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
                'id'   => $this->difficulty->id,
                'name' => $this->difficulty->name,
                'icon' => asset($this->difficulty->icon),
            ],
            'image'          => asset($this->image),
            'created_at'     => $this->created_at,
            'instructions'   => $this->instructions,
            'description'    => $this->description,
            'question_count' => $this->questions_count,
            'points'         => $this->points_count,

            'questions' => $this->whenLoaded('questions', function () {
                return $this->questions->map(function ($question) {
                    return [
                        'id'          => $question->id,
                        'description' => $question->description,
                        'answers'     => $question->answers->map(function ($answer) {
                            return [
                                'id'          => $answer->id,
                                'description' => $answer->description,
                                'is_correct'  => $answer->is_correct,
                            ];
                        }),
                    ];
                });
            }),
        ];
    }
}
