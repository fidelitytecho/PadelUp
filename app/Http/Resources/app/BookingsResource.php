<?php

namespace App\Http\Resources\app;

use App\Http\Resources\UserResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingsResource extends JsonResource
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
            'category' => $this->whenLoaded('Category', function () {
                return new CategoryResource($this->Category);
            }),
            'start_date' => date('d M Y, h:i A', strtotime($this->start_time)),
            'start_date_normal_format' => date('Y-m-d H:i:s', strtotime($this->start_time)),
            'start_time' => date('h:i A', strtotime($this->start_time)),
            'start_date_with_day' => date('D d M Y', strtotime($this->start_time)),
            'end_date' => date('d M Y, h:i A', strtotime($this->end_time)),
            'end_date_normal_format' => date('Y-m-d H:i:s', strtotime($this->end_time)),
            'end_time' => date('h:i A', strtotime($this->end_time)),
            'end_date_with_day' => date('D d M Y', strtotime($this->end_time)),
            'duration' => $this->duration,
            'players' => $this->whenLoaded('Player', function () {
                return $this->Player;
            }),
            'is_discounted' => $this->is_discounted,
            'share' => $this->whenLoaded('Promo', function () {
                return $this->Promo->slug;
            }),
            'court' => $this->whenLoaded('Court', function () {
                return new CourtResource($this->Court);
            }),
            'cost' => $this->cost,
            'currency' => $this->whenLoaded('Currency', function () {
                return $this->Currency->sign_en;
            }),
            'service' => $this->whenLoaded('Service', function () {
                return new ServicesResource($this->Service);
            }),
            'label' => $this->label,
            'paymentMode' => $this->payment_mode == 1 ? 'Wallet' : ($this->payment_mode == 2 ? 'Cash' : 'Card'),
            'purchaseAttemptStatus' => $this->when($this->Payments->first()->payment_mode == 3 && $this->label == 'Pending', function () {
                return true;
            })
        ];
    }
}
