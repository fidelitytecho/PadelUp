<?php

namespace App\Http\Resources\app;

use App\Models\CategoryImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'imageUrl' => asset('storage/' . $this->image_url),
        ];
    }
}
