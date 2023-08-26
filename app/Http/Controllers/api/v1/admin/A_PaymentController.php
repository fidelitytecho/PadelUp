<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\A_RefundAmountRequest;
use App\Http\Requests\ChargePaymentRequest;
use App\Http\Resources\admin\A_PaymentResource;
use App\Repositories\Interfaces\PaymentInterface;
use App\Services\admin\A_RefundAmountService;
use App\Services\ChargeCustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class A_PaymentController extends Controller
{

    private $chargeCustomerService, $refundAmountService, $payment;

    /**
     * Create a new instance.
     * @param ChargeCustomerService $chargeCustomerService
     * @param A_RefundAmountService $refundAmountService
     * @param PaymentInterface $payment
     */
    public function __construct(ChargeCustomerService $chargeCustomerService, A_RefundAmountService $refundAmountService, PaymentInterface $payment)
    {
        $this->chargeCustomerService = $chargeCustomerService;
        $this->refundAmountService = $refundAmountService;
        $this->payment = $payment;
    }

    /**
     * Fetch All Payments
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return A_PaymentResource::collection($this->payment->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChargePaymentRequest $request
     * @return JsonResponse
     */
    public function store(ChargePaymentRequest $request): JsonResponse
    {
        return $this->chargeCustomerService->storePaymentAndPurchase($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param A_RefundAmountRequest $request
     * @return JsonResponse
     */
    public function refund(A_RefundAmountRequest $request): JsonResponse
    {
        return $this->refundAmountService->refund($request->validated());
    }
}
