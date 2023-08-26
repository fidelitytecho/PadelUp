<?php

namespace App\Services\admin;

use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use Illuminate\Http\JsonResponse;

class A_RefundToCustomerWalletService
{
    private $walletHistory;
    private $payment;

    /**
     * Create a new instance.
     * @param WalletHistoryInterface $walletHistory
     * @param PaymentInterface $payment
     */
    public function __construct(WalletHistoryInterface $walletHistory, PaymentInterface $payment)
    {
        $this->walletHistory = $walletHistory;
        $this->payment = $payment;
    }

    /**
     * New Booking Function
     * @param array $data
     * @return JsonResponse
     */
    public function refund(array $data): JsonResponse
    {
        $this->walletHistory->store([
           'customer_id' => $data['customer_id'],
            'amount' => $data['amount']
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
