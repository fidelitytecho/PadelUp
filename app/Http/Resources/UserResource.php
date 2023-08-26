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
            'username' => $this->username,
            'skill_level' => $this->skill_level,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'image' => $this->image !== null ? asset('storage/' . $this->image) : null,
            'isSignedUp' => $this->is_signed_up == 1,
            'customer_id' => $this->whenLoaded('Customer', function () {
                return $this->Customer->id;
            }),
            'walletAmount' => $this->whenLoaded('Customer', function () {
                return $this->Customer->Wallet->sum('amount');
            }),
            'skillAgree' => $this->whenLoaded('Skill', function () {
                $s = $this->skill;
                $agreed = 0;
                foreach ($s as $v) {
                    if ($v['endorse'] == 1||$v['endorse'] == true) {
                        $agreed += 1;
                    }
                }
                return $agreed;
            }),
            'skillDisagree' => $this->whenLoaded('Skill', function () {
                $s = $this->skill;
                $disagreed = 0;
                foreach ($s as $v) {
                    if ($v['endorse'] == 0||$v['endorse'] == false) {
                        $disagreed += 1;
                    }
                }
                return $disagreed; 
            }),
        ];
    }
}
