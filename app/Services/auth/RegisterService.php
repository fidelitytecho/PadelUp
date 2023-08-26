<?php

namespace App\Services\auth;

use App\Events\CreateCustomerEvent;
use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\CustomerInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;

class RegisterService
{
    private $userInterface, $customer, $saveFcmTokenService;

    /**
     * Create a new instance.
     *
     * @param UserInterface $userInterface
     * @param CustomerInterface $customer
     * @param SaveFcmTokenService $saveFcmTokenService
     */
    public function __construct(UserInterface $userInterface, CustomerInterface $customer, SaveFcmTokenService $saveFcmTokenService)
    {
        $this->userInterface = $userInterface;
        $this->customer = $customer;
        $this->saveFcmTokenService = $saveFcmTokenService;
    }

    /**
     * Handel User Data
     * @param array $data
     * @param string|null $fcmToken
     * @return JsonResponse
     */
    public function register(array $data, string $fcmToken = null): JsonResponse
    {
        \DB::beginTransaction();
        try {
            // Create User Date
            $createdUser = $this->userInterface->create($data);

            // Create Customer Data
            $this->customer->create([
                'user_id' => $createdUser->id,
                'company_id' => 1,
            ]);

            // Assign Customer Role
            $createdUser->assignRole('Customer');

            // Add Fcm Token
            if ($fcmToken !== null) {
                $this->saveFcmTokenService->saveToken($fcmToken, $createdUser->id);
            }

            \DB::commit();

            auth()->loginUsingId($createdUser->id);
            $returnArray = [
                'success'=> true,
                'message'=> 'Created Successfully',
                'data' => new UserResource($createdUser->load('Customer.Wallet')),
                'token' => auth('api')->tokenById($createdUser->id)
            ];

            return response()->json($returnArray, 201);
        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to register',
            ]);
            return response()->json($output, 500);
        }
    }
}
