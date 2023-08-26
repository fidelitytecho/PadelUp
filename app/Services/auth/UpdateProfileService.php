<?php

namespace App\Services\auth;

use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;

class UpdateProfileService
{
    private $user;

    /**
     * Create a new instance.
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Login User With Mobile
     * @param array $data
     * @param int $id
     * @return UserResource
     */
    public function updateProfile(array $data, int $id): UserResource
    {
        $updatedUser = $this->user->update($data, $id);
        return new UserResource($updatedUser->load('Customer.Wallet'));
    }
}
