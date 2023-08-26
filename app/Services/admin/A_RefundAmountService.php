<?php

namespace App\Services\admin;

use App\Http\Resources\admin\A_PaymentResource;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use Illuminate\Http\JsonResponse;

class A_RefundAmountService
{
    private $payment;
    private $walletHistory;

    /**
     * Create a new instance.
     * @param PaymentInterface $payment
     * @param WalletHistoryInterface $walletHistory
     */
    public function __construct(PaymentInterface $payment, WalletHistoryInterface $walletHistory)
    {
        $this->payment = $payment;
        $this->walletHistory = $walletHistory;
    }

    /**
     * Display a listing of the resource.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function refund(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {

            // Fetch Payment Data
            $paymentData = $this->payment->findByID($data['payment_id']);

            if ($paymentData->refunded == 0) {
                // Update Payment Data
                $updatedPayment = $this->payment->update($data['payment_id'], $data['refund_data']);

                // Add to Customer Wallet
                $this->walletHistory->store([
                    'customer_id' => $updatedPayment->Booking->customer_id,
                    'amount' => $data['refund_data']['refunded']
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message'=> 'Payment Refunded Successfully',
                    'data'=> new A_PaymentResource($updatedPayment->load('Purchases')),
                ]);
            }

            return response()->json([
                'success' => true,
                'message'=> 'This payment already refunded',
            ], 400);

        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to refund the amount'
            ]);
            return response()->json($output, 500);
        }
    }
}
