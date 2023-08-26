<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_EventResource extends JsonResource
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
            'court_name' => $this->Court->name_en,
            'event_title' => $this->title,
            'start_date_time' => date('Y-m-d h:i a', strtotime($this->start_time)),
            'end_date_time' => date('Y-m-d h:i a', strtotime($this->end_time)),
        ];
    }
}
