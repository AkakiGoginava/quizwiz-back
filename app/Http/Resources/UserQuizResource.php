<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserQuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'quiz_id'       => $this->id,
            'points'        => $this->pivot->points,
            'complete_time' => $this->pivot->complete_time,
            'complete_date' => $this->pivot->created_at,
        ];
    }
}
