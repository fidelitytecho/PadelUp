<?php

namespace App\Services\auth;

use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;

class CheckUserExistService
{
    private $user;

    /**
     * Create a new instance.
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Check If User Exist Before
     *
     * @param array $data
     * @return JsonResponse
     */
    public function checkUserExist(array $data): JsonResponse
    {
        try {
            $output = [];
            $userData = $this->user->show($data['user'], $data['role']);
            if (!$userData) {
                $output['exist'] = false;
                $output['message'] = 'User not exist';
                return response()->json($output);
            }

            $output['exist'] = true;
            $output['message'] = 'User registered before';
            $output['data'] = new UserResource($userData->load('Customer.Wallet'));
            return response()->json($output);
        } catch (\Exception $e) {
            $output = ([
                'success' => false,
                'message'=> 'Error while Finding User',
            ]);
            return response()->json($output, 500);
        }
    }
}
