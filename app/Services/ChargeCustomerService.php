<?php

namespace App\Services;

use App\Http\Resources\admin\A_PaymentResource;
use App\Models\WalletHistory;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\PurchaseAttemptInterface;
use App\Repositories\Interfaces\PurchaseInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ChargeCustomerService
{
    private $payment;
    private $purchase;
    private $walletHistory;
    private $booking;
    private $purchaseAttempt;

    /**
     * Create a new instance.
     * @param PaymentInterface $payment
     * @param PurchaseInterface $purchase
     * @param WalletHistoryInterface $walletHistory
     * @param BookingInterface $booking
     * @param PurchaseAttemptInterface $purchaseAttempt
     */
    public function __construct(
        PaymentInterface $payment, PurchaseInterface $purchase,
        WalletHistoryInterface $walletHistory, BookingInterface $booking, PurchaseAttemptInterface $purchaseAttempt)
    {
        $this->payment = $payment;
        $this->purchase = $purchase;
        $this->walletHistory = $walletHistory;
        $this->booking = $booking;
        $this->purchaseAttempt = $purchaseAttempt;
    }

    /**
     * Fetch Category Data
     * @param array $data
     * @return JsonResponse
     */
    public function storePaymentAndPurchase(array $data): JsonResponse
    {
        \DB::beginTransaction();
        try {
            // Check Booking Data
            $bookingData = $this->booking->findByID($data['bookingID']);
            if($bookingData) {

                // Store Payment Data
                $createdPayment = $this->payment->store([
                    'booking_id' => $data['bookingID'],
                    'payment_mode' => $data['payment_type'],
                ]);

                if ($createdPayment) {

                    foreach ($data['fees'] as $fee) {

                        // Store Purchase Data
                        $createdPurchase = $this->purchase->store([
                            'payment_id' => $createdPayment->id,
                            'customer_id' => $bookingData->customer_id,
                            'payment_mode_id' => $data['payment_type'],
                            'title' => $fee['title'],
                            'amount' => $fee['amount'],
                            'isDiscount' => $fee['isDiscount']
                        ]);

                        // Store Purchase Attempt Data
                        $this->purchaseAttempt->store([
                            'purchase_id' => $createdPurchase->id,
                            'payment_status' => true,
                        ]);

                    }

                    // Catch Amount from wallet if payment type = wallet
                    if ($data['payment_type'] == 1) {
                        $this->walletHistory->store([
                            'customer_id' => $bookingData->customer_id,
                            'amount' => -$data['total'],
                        ]);
                    }

                }

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message'=> 'Payment Charged Successfully',
                    'data'=> new A_PaymentResource($createdPayment->load('Purchases')),
                ]);
            }

            return response()->json([
                'success' => false,
                'message'=> 'Booking Not Found',
            ], 404);

        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to Charge Customer',
                'error' => $ex
            ]);
            return response()->json($output, 500);
        }
    }
}
