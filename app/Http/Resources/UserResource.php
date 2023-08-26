<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->full_name,
            'email' => $this->email,
            'mobile' => $this->full_mobile,
            'short_mobile' => $this->mobile,
            'dialCode' => '+' . $this->dial_code,
            'isSignedUp' => $this->is_signed_up == 1,
            'customer_id' => $this->whenLoaded('Customer', function () {
                return $this->Customer->id;
            }),
            'walletAmount' => $this->whenLoaded('Customer', function () {
                return $this->Customer->Wallet->sum('amount');
            })
        ];
    }
}
