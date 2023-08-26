<?php

namespace App\Services\auth;

use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;

class LoginWithMobileService
{
    private $guard;
    private $saveFcmTokenService;
    private $user;

    /**
     * Create a new instance.
     */
    public function __construct(SaveFcmTokenService $saveFcmTokenService, UserInterface $user)
    {
        $this->guard = "api";
        $this->saveFcmTokenService = $saveFcmTokenService;
        $this->user = $user;
    }

    /**
     * Login User With Mobile
     * @param string $mobile
     * @param string|null $fcmToken
     * @return JsonResponse
     */
    public function loginWithMobile(string $mobile, string $fcmToken = null): JsonResponse
    {
        $userData = $this->user->show([
            'mobile' => $mobile
        ], 'Customer');

        if ($userData) {
            auth()->login($userData);
            $user = auth()->user();

            if ($fcmToken !== null) {
                $this->saveFcmTokenService->saveToken($fcmToken, auth($this->guard)->id());
            }

            return response()->json([
                'success' => true,
                'data' => new UserResource($user->load('Customer.Wallet', 'Skill')),
                'token' => auth('api')->tokenById($userData->id)
            ]);
        }

        return response()->json(['success' => false,
            'message'=> 'User Not Exist'],
            404);
    }
}
