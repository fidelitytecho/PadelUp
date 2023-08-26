<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'name' => $this->User->full_name,
            'mobile' => $this->User->full_mobile,
            'walletAmount' => $this->whenLoaded('Wallet', function () {
                return $this->Wallet->sum('amount');
            }),
        ];
    }
}
