<?php

namespace App\Services;
use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use App\Services\auth\RegisterService;
use App\Services\auth\SaveFcmTokenService;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Symfony\Component\HttpFoundation\JsonResponse;

class FirebaseService
{
    private $auth, $user, $registerService, $saveFcmTokenService;
    public function __construct(UserInterface $user, RegisterService $registerService, SaveFcmTokenService $saveFcmTokenService)
    {
        $this->auth = app('firebase.auth');
        // $this->auth = Firebase::Auth();
        $this->user = $user;
        $this->registerService = $registerService;
        $this->saveFcmTokenService = $saveFcmTokenService;
    }

    public function authenticateFirebase ($token)
    {
        $response =  [];
        $response['fcmtoken'] = $token;

        try {
            $verifiedIdToken = $this->auth->verifyIdToken($token);
        }catch (FailedToVerifyToken $e) {
            return response()->json('The token is invalid: '. $e->getMessage());
        }
        $email = $verifiedIdToken->claims()->get('email');
        $response['email'] = $email;
        // $uid = $verifiedIdToken->claims()->get('sub');
        // $response->uid = $uid;
        $name = $verifiedIdToken->claims()->get('displayName');
        $response['first_name'] = $name;

        $user = $this->user->find($token);

        if (!$user)
        {
            $this->registerService->register($response, $token);
        }else {
            $this->login($user);
        }

    }

    public function login($credentials, string $fcmToken = null): JsonResponse
    {
        if ($token = auth('api')->login($credentials)) {

            if ($fcmToken !== null) {
                $this->saveFcmTokenService->saveToken($fcmToken, auth('api')->id());
            }

            $userData = auth('api')->user();

            $returnArray = [
                'success' => true,
                'token' => $token,
                'data' => $userData->hasRole(['Customer']) ? new UserResource(auth('api')->user()->load('Customer.Wallet', 'Skill')) : null
            ];

            return response()->json($returnArray);
        }

        return response()->json(['success' => false,
            'message'=> 'The username and/or password used for authentication are invalid'],
            404);
    }
}
