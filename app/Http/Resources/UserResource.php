<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        Log::info($this->quizzes);

        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'email'   => $this->email,
            'image'   => asset($this->image),
            'quizzes' => $this->quizzes->map(function ($quiz) {
                return [
                    'quiz_id'       => $quiz->id,
                    'points'        => $quiz->pivot->points,
                    'complete_time' => $quiz->pivot->complete_time,
                    'complete_date' => $quiz->pivot->created_at,
                ];
            }),
        ];
    }
}
