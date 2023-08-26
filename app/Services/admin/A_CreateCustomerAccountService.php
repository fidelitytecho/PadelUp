<?php

namespace App\Services\admin;

use App\Events\CreateCustomerEvent;
use App\Http\Resources\UserResource;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\CustomerInterface;
use Illuminate\Http\JsonResponse;

class A_CreateCustomerAccountService
{
    private $userInterface;
    private $customer;

    /**
     * Create a new instance.
     *
     * @param UserInterface $userInterface
     */
    public function __construct(UserInterface $userInterface, CustomerInterface $customer)
    {
        $this->userInterface = $userInterface;
        $this->customer = $customer;
    }

    /**
     * Handel User Data
     * @param array $data
     * @return JsonResponse
     */
    public function createCustomerAccount(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            $createdUser = $this->userInterface->create($data);

            // Create Customer Data
            $this->customer->create([
                'user_id' => $createdUser->id,
                'company_id' => 1,
            ]);

            // Assign Customer Role
            $createdUser->assignRole('Customer');

            \DB::commit();

            $returnArray = [
                'success'=> true,
                'message'=> 'Created Successfully',
                'data' => new UserResource($createdUser->load('Customer.Wallet', 'Skill')),
            ];

            return response()->json($returnArray, 201);
        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to create customer account',
            ]);
            return response()->json($output, 500);
        }
    }
}
