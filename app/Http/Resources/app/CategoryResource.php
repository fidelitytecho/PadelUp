<?php

namespace App\Http\Resources\app;

use App\Services\FetchCategoryAvailableTimesService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name_en,
            'address' => [
                'fullAddress' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            ],
            'description' => $this->description_en,
            'price' => 400,
            'courts' => $this->whenLoaded('Courts', function () {
                return CourtResource::collection($this->Courts);
            }),
            'images' => $this->whenLoaded('Images', function () {
                return ImageResource::collection($this->Images);
            }),
            'services' => $this->whenLoaded('Services', function () {
                return ServicesResource::collection($this->Services);
            }),
            'currency' => $this->Company->Currency->sign_en
       ];
    }
}
