<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Traits\CheckBookingOverlapTrait;
use App\Repositories\Interfaces\PaymobInterface;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\ServiceInterface;
use App\Jobs\CheckPurchaseAttemptPaymentStatusJob;
use App\Repositories\Interfaces\PurchaseInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use App\Repositories\Interfaces\PurchaseAttemptInterface;

class BookService
{
    use CheckBookingOverlapTrait;
    private $booking;
    private $service;
    private $paymob;
    private $payment;
    private $purchase;
    private $purchaseAttempt;
    private $walletHistory;

    /**
     * Create a new instance.
     * @param BookingInterface $booking
     * @param ServiceInterface $service
     * @param PaymobInterface $paymob
     * @param PaymentInterface $payment
     * @param PurchaseInterface $purchase
     * @param PurchaseAttemptInterface $purchaseAttempt
     * @param WalletHistoryInterface $walletHistory
     */
    public function __construct(BookingInterface $booking, ServiceInterface $service,
                                PaymobInterface $paymob, PaymentInterface $payment,
                                PurchaseInterface $purchase, PurchaseAttemptInterface $purchaseAttempt, WalletHistoryInterface $walletHistory)
    {
        $this->booking = $booking;
        $this->service = $service;
        $this->paymob = $paymob;
        $this->payment = $payment;
        $this->purchase = $purchase;
        $this->purchaseAttempt = $purchaseAttempt;
        $this->walletHistory = $walletHistory;
    }

    /**
     * New Booking Function
     * @param array $data
     * @return JsonResponse
     */
    public function book(array $data): JsonResponse
    {

        \DB::beginTransaction();
        try {
            // Check If there's no overlapping
            if ($this->isBookingOverlap($data)) {
                $data['label'] = 'Pending';
                $data['end_time'] = Carbon::parse($data['start_time'])->addMinutes($data['duration'])->toDateTimeString();
                // $data['end_time'] = date('Y-m-d H:i:s', strtotime('+' . $data['duration'] . ' minutes', strtotime($data['start_time'])));
                if ($data['payment_mode'] == 2 || $data['payment_mode'] == 1) {
                    $data['status'] = true;
                }
                if($data['payment_mode'] == 2) {
                    $data['label'] = 'Cash payment';
                }
                // Create Booking
                $createdBooking = $this->createBooking($data);

                \DB::commit();

                $output = [
                    'success' => true,
                ];

                $serviceData = $this->service->findByID($data['service_id']);

                // Check If Payment Visa
                if ($createdBooking) {

                    $userWalletAmount = (double)auth('api')->user()->load('Customer')->Customer->Wallet->sum('amount');

                    if ($data['payment_mode'] == 3 || ($data['payment_mode'] == 1 && $userWalletAmount < $createdBooking->cost)) {
                        // Add Payment Function
                        $createdPayment = $this->createPayment($createdBooking->id, $data['payment_mode']);

                        // Add Purchase
                        $createdPurchase = $this->createPurchase(
                            $createdPayment->id,
                            $data['payment_mode'] == 1 && $userWalletAmount < $createdBooking->cost ? $createdBooking->cost - $userWalletAmount : $createdBooking->cost,
                            $data['payment_mode'] == 1 && $userWalletAmount < $createdBooking->cost ? 3 : $data['payment_mode'], $serviceData->name_en);

                        // Add Purchase Attempt
                        $createdPurchaseAttempt = $this->createPurchaseAttempt($createdPurchase->id);

                        $createdID = config('app.env') == 'production' ? $createdPurchaseAttempt->id . 'padelUpLive' : $createdPurchaseAttempt->id . 'padelUpTest';
                        $paymobAmount = $createdBooking->cost;
                        if($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                            $paymobAmount = $paymobAmount + -$userWalletAmount;
                        }
                        $paymobArray = $this->paymob->weAcceptPayingProcess($paymobAmount, $createdID, auth('api')->user());

                        if ($paymobArray) {
                            CheckPurchaseAttemptPaymentStatusJob::dispatch($createdPurchaseAttempt, $createdBooking)->delay(now()->addSeconds(config('app.WE_ACCEPT_EXPIRATION_ORDER_SECONDS')));

                            $createdPurchaseAttempt->update([
                                'paymob_order_id' => $paymobArray['orderID'],
                                'paymob_iframe_token' => $paymobArray['token']
                            ]);
                            $output['paymentToken'] = $paymobArray['token'];
                            $output['iframeID'] = config('app.WE_ACCEPT_IFRAME_ID');

                            if($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                                // Add Wallet Payment Function
                                $walletCreatedPayment = $this->createPayment($createdBooking->id, 1);

                                // Add Wallet Purchase
                                $walletCreatedPurchase = $this->createPurchase($walletCreatedPayment->id, -$userWalletAmount, 1, $serviceData->name_en);

                                // Add Wallet Purchase Attempt
                                $this->purchaseAttempt->store([
                                    'purchase_id' => $walletCreatedPurchase->id,
                                    'paymob_order_id' => $paymobArray['orderID']
                                ]);
                            }
                        }
                    }

                    // Charge Wallet
                    if ($data['payment_mode'] == 1 && $userWalletAmount >= $createdBooking->cost){
                        $this->walletHistory->store([
                            'customer_id' => $createdBooking->customer_id,
                            'amount' => -$createdBooking->cost
                        ]);

                        // Update Booking
                        $createdBooking->update([
                            'label' => 'Confirmed'
                        ]);

                        // Update Purchase Attempt
                        $createdPurchaseAttempt->update([
                            'payment_status' => true
                        ]);
                    }
                }
                return response()->json($output);
            }

            $output = ([
                'success' => false,
                'message'=> 'Unfortunately the time slot you have selected has been taken. Please select a different court or timeslot'
            ]);
            return response()->json($output, 400);
        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to create bookings',
            ]);
            return response()->json($output, 500);
        }
    }

    /**
     * Check Booking Overlap Function
     * @param array $data
     * @return bool
     */
    public function isBookingOverlap(array $data): bool
    {
        $searchData = [
            'court_id' => $data['court_id'],
            'start_time' => date('Y-m-d H:i:s', strtotime($data['start_time'])),
            'end_time' => date('Y-m-d H:i:s', strtotime('+' . $data['duration'] . ' minutes', strtotime($data['start_time'])))
        ];

        return $this->checkBookingOverlap($searchData);
    }

    /**
     * Create Booking Function
     * @param array $data
     * @return mixed
     */
    public function createBooking(array $data)
    {
        $serviceData = $this->service->findByID($data['service_id']);

        $data['customer_id'] = auth('api')->user()->load('Customer')->Customer->id;
        $data['cost'] = $serviceData->cost;
        $data['duration'] = $serviceData->duration;
        $data['start_time'] = date('Y-m-d H:i:s', strtotime($data['start_time']));

        return $this->booking->store($data);
    }

    /**
     * Create Payment Function
     * @param int $bookingID
     * @param int $paymentMode
     * @return mixed
     */
    public function createPayment(int $bookingID, int $paymentMode)
    {
        return $this->payment->store([
            'booking_id' => $bookingID,
            'payment_mode' => $paymentMode
        ]);
    }

    /**
     * Create Purchase Function
     * @param $paymentID
     * @param $cost
     * @param int $paymentMode
     * @param string $title
     * @return mixed
     */
    public function createPurchase($paymentID, $cost, int $paymentMode, string $title)
    {
        return $this->purchase->store([
            'title' => $title,
            'customer_id' => auth('api')->user()->load('Customer')->Customer->id,
            'payment_id' => $paymentID,
            'amount' => $cost,
            'payment_mode_id' => $paymentMode
        ]);
    }

    /**
     * Create Purchase Attempt Function
     * @param int $purchaseID
     * @return mixed
     */
    public function createPurchaseAttempt(int $purchaseID)
    {
        return $this->purchaseAttempt->store([
            'purchase_id' => $purchaseID,
        ]);
    }

}
