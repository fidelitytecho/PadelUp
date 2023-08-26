<?php

namespace App\Http\Resources\app;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'rate' => $this->rate,
            'comment' => $this->comment,
            'user' => [
                'name' => $this->Customer->User->full_name
            ],
            'created' => date('d M, Y', strtotime($this->created_at))
        ];
    }
}
