<?php

namespace App\Http\Resources\admin;

use App\Http\Resources\app\CourtResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_BookingResource extends JsonResource
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
            'customer' => $this->whenLoaded('Customer', function () {
                return new A_CustomerResource($this->Customer);
            }),
            'service' => $this->whenLoaded('Service', function () {
                return new A_ServiceResource($this->Service);
            }),
            'cost' => $this->cost,
            'status' => $this->label,
            'duration' => $this->duration,
            'currency' => $this->whenLoaded('Currency', function () {
                return $this->Currency->sign_en;
            }),
            'court' => $this->whenLoaded('Court', function () {
                return new A_CourtResource($this->Court);
            }),
            'startDate' => date('Y-m-d', strtotime($this->start_time)),
            'startTime24Hr' => date('H:i:s', strtotime($this->start_time)),
            'startTime12Hr' => date('h:i a', strtotime($this->start_time)),
            'endDate' => date('Y-m-d', strtotime($this->end_time)),
            'endTime24Hr' => date('H:i:s', strtotime($this->end_time)),
            'endTime12Hr' => date('h:i a', strtotime($this->end_time)),
            'allPayments' => $this->whenLoaded('Purchases', function () {
                return $this->Purchases->sum('amount');
            }),
            'payments' => $this->whenLoaded('Payments', function () {
                return A_PaymentResource::collection($this->Payments);
            })
        ];
    }
}
