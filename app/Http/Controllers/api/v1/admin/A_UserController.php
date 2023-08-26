<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\A_CreateCustomerRequest;
use App\Http\Resources\admin\A_CustomerResource;
use App\Models\Customer;
use App\Services\admin\A_CreateCustomerAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class A_UserController extends Controller
{
    private $customerAccountService;

    /**
     * Create a new instance.
     * @param A_CreateCustomerAccountService $customerAccountService
     */
    public function __construct(A_CreateCustomerAccountService $customerAccountService)
    {
        $this->customerAccountService = $customerAccountService;
    }

    /**
     * Fetch All Customers
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return A_CustomerResource::collection(Customer::with('Wallet')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param A_CreateCustomerRequest $request
     * @return JsonResponse
     */
    public function store(A_CreateCustomerRequest $request): JsonResponse
    {
        return $this->customerAccountService->createCustomerAccount($request->validated()['user']);
    }
}
