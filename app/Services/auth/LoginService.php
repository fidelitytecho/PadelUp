<?php

namespace App\Services\auth;

use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;

class LoginService
{
    private $guard;
    private $saveFcmTokenService;

    /**
     * Create a new instance.
     */
    public function __construct(SaveFcmTokenService $saveFcmTokenService)
    {
        $this->guard = "api";
        $this->saveFcmTokenService = $saveFcmTokenService;
    }

    /**
     * Login User
     * @param array $credentials
     * @param string|null $fcmToken
     * @return JsonResponse
     */
    public function login(array $credentials, string $fcmToken = null): JsonResponse
    {
        if ($token = auth($this->guard)->attempt($credentials)) {

            if ($fcmToken !== null) {
                $this->saveFcmTokenService->saveToken($fcmToken, auth($this->guard)->id());
            }

            $userData = auth($this->guard)->user();

            $returnArray = [
                'success' => true,
                'token' => $token,
                'data' => $userData->hasRole(['Customer']) ? new UserResource(auth($this->guard)->user()->load('Customer.Wallet')) : null
            ];

            return response()->json($returnArray);
        }

        return response()->json(['success' => false,
            'message'=> 'The username and/or password used for authentication are invalid'],
            404);
    }
}
