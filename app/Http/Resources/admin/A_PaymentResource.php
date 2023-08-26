<?php

namespace App\Http\Resources\admin;

use App\Http\Resources\app\BookingsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_PaymentResource extends JsonResource
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
            'date' => date('M d, Y', strtotime($this->created_at)),
            'booking_id' => $this->whenLoaded('Booking', function () {
                return $this->Booking->id;
            }),
            'booking' => new A_BookingResource($this->Booking),
            'currency' => 'EGP',
            'refunded' => $this->refunded > 0,
            'refunded_amount' => $this->when($this->refunded > 0, function () {
                return $this->refunded;
            }),
            'payment_mode' => $this->PaymentMode->name_en,
            'amount' => $this->whenLoaded('Purchases', function () {
                return $this->Purchases->sum('amount');
            }),
            'purchases' => $this->whenLoaded('Purchases', function () {
                return A_PurchaseResource::collection($this->Purchases);
            }),
        ];
    }
}
