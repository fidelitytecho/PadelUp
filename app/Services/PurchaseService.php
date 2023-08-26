<?php

namespace App\Services;

use App\Jobs\CheckPurchaseAttemptPaymentStatusJob;
use App\Repositories\Interfaces\AcademyInterface;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\PaymobInterface;
use App\Repositories\Interfaces\PlaysetInterface;
use App\Repositories\Interfaces\ProductInterface;
use App\Repositories\Interfaces\PurchaseAttemptInterface;
use App\Repositories\Interfaces\PurchaseInterface;
use App\Repositories\Interfaces\ReceiptInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;

class PurchaseService
{
    private $purchase, $purchaseAttempt, $paymob, $payment, $walletHistory, $receipt, $product, $playset, $academy;

    /**
     * Summary of __construct
     * @param PurchaseInterface $purchase
     * @param PurchaseAttemptInterface $purchaseAttempt
     * @param PaymobInterface $paymob
     * @param PaymentInterface $payment
     * @param WalletHistoryInterface $walletHistory
     * @param ReceiptInterface $receipt
     * @param ProductInterface $product
     * @param PlaysetInterface $playset
     * @param AcademyInterface $academy
     */
    public function __construct(
        PurchaseInterface $purchase,
        PurchaseAttemptInterface $purchaseAttempt, PaymobInterface $paymob, PaymentInterface $payment,
        WalletHistoryInterface $walletHistory, ReceiptInterface $receipt, ProductInterface $product,
        PlaysetInterface $playset, AcademyInterface $academy
    ) {
        $this->purchase = $purchase;
        $this->purchaseAttempt = $purchaseAttempt;
        $this->paymob = $paymob;
        $this->payment = $payment;
        $this->walletHistory = $walletHistory;
        $this->receipt = $receipt;
        $this->product = $product;
        $this->playset = $playset;
        $this->academy = $academy;
    }

    public function purchaseProduct(array $data)
    {
        \DB::beginTransaction();
        try {
            $data['label'] = 'Pending';
            $product = $this->product->findByID($data['product_id']);
            $createdReceipt = $this->receipt->store([
                'product_id' => $product->id,
                'cost' => $product->cost,
                'name' => $product->name,
                'customer_id' => auth('api')->user()->load('Customer')->Customer->id
            ]);
            \DB::commit();
            $output = [
                'success' => true,
            ];
            $userWalletAmount = (double) auth('api')->user()->load('Customer')->Customer->Wallet->sum('amount');
            if ($data['payment_mode'] == 3 || ($data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost)) {
                $createdPayment = $this->createPayment(productID: $createdReceipt->id, paymentMode: $data['payment_mode']);
                $createdPurchase = $this->createPurchase(
                    $createdPayment->id,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? $createdReceipt->cost - $userWalletAmount : $createdReceipt->cost,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? 3 : $data['payment_mode'], $createdReceipt->name
                );

                $createdPurchaseAttempt = $this->createPurchaseAttempt($createdPurchase->id);
                $createdID = config('app.env') == 'production' ? $createdPurchaseAttempt->id . 'padelUpLive' : $createdPurchaseAttempt->id . 'padelUpTest';
                $paymobAmount = $createdReceipt->cost;
                if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                    $paymobAmount = $paymobAmount + -$userWalletAmount;
                }
                $paymobArray = $this->paymob->weAcceptPayingProcess($paymobAmount, $createdID, auth('api')->user());

                if ($paymobArray) {
                    CheckPurchaseAttemptPaymentStatusJob::dispatch($createdPurchaseAttempt, $createdReceipt)->delay(now()->addSeconds(config('app.WE_ACCEPT_EXPIRATION_ORDER_SECONDS')));

                    $createdPurchaseAttempt->update([
                        'paymob_order_id' => $paymobArray['orderID'],
                        'paymob_iframe_token' => $paymobArray['token']
                    ]);
                    $output['paymentToken'] = $paymobArray['token'];
                    $output['iframeID'] = config('app.WE_ACCEPT_IFRAME_ID');

                    if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                        // Add Wallet Payment Function
                        $walletCreatedPayment = $this->createPayment($createdReceipt->id, 1);

                        // Add Wallet Purchase
                        $walletCreatedPurchase = $this->createPurchase($walletCreatedPayment->id, -$userWalletAmount, 1, $createdReceipt->name);

                        // Add Wallet Purchase Attempt
                        $this->purchaseAttempt->store([
                            'purchase_id' => $walletCreatedPurchase->id,
                            'paymob_order_id' => $paymobArray['orderID']
                        ]);
                    }
                }
            }

            if ($data['payment_mode'] == 1 && $userWalletAmount >= $createdReceipt->cost) {
                $this->walletHistory->store([
                    'customer_id' => $createdReceipt->customer_id,
                    'amount' => -$createdReceipt->cost
                ]);

                // Update Booking
                $createdReceipt->update([
                    'label' => 'Confirmed'
                ]);

                // Update Purchase Attempt
                $createdPurchaseAttempt->update([
                    'payment_status' => true
                ]);
            }
            return response()->json($output);
        }
        catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to create bookings',
            ]);
            return response()->json($output, 500);
        }
    }

    public function purchaseAcademy(array $data)
    {
        \DB::beginTransaction();
        try {
            $data['label'] = 'Pending';
            $academy = $this->academy->findByID($data['academy_id']);
            $createdReceipt = $this->receipt->store([
                'academy_id' => $academy->id,
                'cost' => $academy->cost,
                'name' => $academy->name,
                'customer_id' => auth('api')->user()->load('Customer')->Customer->id
            ]);
            \DB::commit();
            $output = [
                'success' => true,
            ];
            $userWalletAmount = (double) auth('api')->user()->load('Customer')->Customer->Wallet->sum('amount');
            if ($data['payment_mode'] == 3 || ($data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost)) {
                $createdPayment = $this->createPayment(academyID: $createdReceipt->id, paymentMode: $data['payment_mode']);
                $createdPurchase = $this->createPurchase(
                    $createdPayment->id,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? $createdReceipt->cost - $userWalletAmount : $createdReceipt->cost,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? 3 : $data['payment_mode'], $createdReceipt->name
                );

                $createdPurchaseAttempt = $this->createPurchaseAttempt($createdPurchase->id);
                $createdID = config('app.env') == 'production' ? $createdPurchaseAttempt->id . 'padelUpLive' : $createdPurchaseAttempt->id . 'padelUpTest';
                $paymobAmount = $createdReceipt->cost;
                if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                    $paymobAmount = $paymobAmount + -$userWalletAmount;
                }
                $paymobArray = $this->paymob->weAcceptPayingProcess($paymobAmount, $createdID, auth('api')->user());

                if ($paymobArray) {
                    CheckPurchaseAttemptPaymentStatusJob::dispatch($createdPurchaseAttempt, $createdReceipt)->delay(now()->addSeconds(config('app.WE_ACCEPT_EXPIRATION_ORDER_SECONDS')));

                    $createdPurchaseAttempt->update([
                        'paymob_order_id' => $paymobArray['orderID'],
                        'paymob_iframe_token' => $paymobArray['token']
                    ]);
                    $output['paymentToken'] = $paymobArray['token'];
                    $output['iframeID'] = config('app.WE_ACCEPT_IFRAME_ID');

                    if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                        // Add Wallet Payment Function
                        $walletCreatedPayment = $this->createPayment($createdReceipt->id, 1);

                        // Add Wallet Purchase
                        $walletCreatedPurchase = $this->createPurchase($walletCreatedPayment->id, -$userWalletAmount, 1, $createdReceipt->name);

                        // Add Wallet Purchase Attempt
                        $this->purchaseAttempt->store([
                            'purchase_id' => $walletCreatedPurchase->id,
                            'paymob_order_id' => $paymobArray['orderID']
                        ]);
                    }
                }
            }

            if ($data['payment_mode'] == 1 && $userWalletAmount >= $createdReceipt->cost) {
                $this->walletHistory->store([
                    'customer_id' => $createdReceipt->customer_id,
                    'amount' => -$createdReceipt->cost
                ]);

                // Update Booking
                $createdReceipt->update([
                    'label' => 'Confirmed'
                ]);

                // Update Purchase Attempt
                $createdPurchaseAttempt->update([
                    'payment_status' => true
                ]);
            }
            return response()->json($output);
        }
        catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to create bookings',
            ]);
            return response()->json($output, 500);
        }
    }

    public function purchasePlayset(array $data)
    {
        \DB::beginTransaction();
        try {
            $data['label'] = 'Pending';
            $playset = $this->playset->findByID($data['playset_id']);
            $createdReceipt = $this->receipt->store([
                'play_set_id' => $playset->id,
                'cost' => $playset->cost,
                'name' => $playset->name,
                'customer_id' => auth('api')->user()->load('Customer')->Customer->id
            ]);
            \DB::commit();
            $output = [
                'success' => true,
            ];
            $userWalletAmount = (double) auth('api')->user()->load('Customer')->Customer->Wallet->sum('amount');
            if ($data['payment_mode'] == 3 || ($data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost)) {
                $createdPayment = $this->createPayment(playsetID: $createdReceipt->id, paymentMode: $data['payment_mode']);
                $createdPurchase = $this->createPurchase(
                    $createdPayment->id,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? $createdReceipt->cost - $userWalletAmount : $createdReceipt->cost,
                    $data['payment_mode'] == 1 && $userWalletAmount < $createdReceipt->cost ? 3 : $data['payment_mode'], $createdReceipt->name
                );

                $createdPurchaseAttempt = $this->createPurchaseAttempt($createdPurchase->id);
                $createdID = config('app.env') == 'production' ? $createdPurchaseAttempt->id . 'padelUpLive' : $createdPurchaseAttempt->id . 'padelUpTest';
                $paymobAmount = $createdReceipt->cost;
                if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                    $paymobAmount = $paymobAmount + -$userWalletAmount;
                }
                $paymobArray = $this->paymob->weAcceptPayingProcess($paymobAmount, $createdID, auth('api')->user());

                if ($paymobArray) {
                    CheckPurchaseAttemptPaymentStatusJob::dispatch($createdPurchaseAttempt, $createdReceipt)->delay(now()->addSeconds(config('app.WE_ACCEPT_EXPIRATION_ORDER_SECONDS')));

                    $createdPurchaseAttempt->update([
                        'paymob_order_id' => $paymobArray['orderID'],
                        'paymob_iframe_token' => $paymobArray['token']
                    ]);
                    $output['paymentToken'] = $paymobArray['token'];
                    $output['iframeID'] = config('app.WE_ACCEPT_IFRAME_ID');

                    if ($userWalletAmount != 0 && $data['payment_mode'] !== 3) {
                        // Add Wallet Payment Function
                        $walletCreatedPayment = $this->createPayment($createdReceipt->id, 1);

                        // Add Wallet Purchase
                        $walletCreatedPurchase = $this->createPurchase($walletCreatedPayment->id, -$userWalletAmount, 1, $createdReceipt->name);

                        // Add Wallet Purchase Attempt
                        $this->purchaseAttempt->store([
                            'purchase_id' => $walletCreatedPurchase->id,
                            'paymob_order_id' => $paymobArray['orderID']
                        ]);
                    }
                }
            }

            if ($data['payment_mode'] == 1 && $userWalletAmount >= $createdReceipt->cost) {
                $this->walletHistory->store([
                    'customer_id' => $createdReceipt->customer_id,
                    'amount' => -$createdReceipt->cost
                ]);

                // Update Booking
                $createdReceipt->update([
                    'label' => 'Confirmed'
                ]);

                // Update Purchase Attempt
                $createdPurchaseAttempt->update([
                    'payment_status' => true
                ]);
            }
            return response()->json($output);
        }
        catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to register purchase',
                'error' => $ex
            ]);
            return response()->json($output, 500);
        }
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

    /**
     * Create Payment Function
     * @param int $bookingID
     * @param int $paymentMode
     * @return mixed
     */
    public function createPayment(int $productID = null, int $playsetID = null, int $academyID = null, int $paymentMode)
    {
        return $this->payment->store([
            'booking_id' => null,
            'merch_id' => $productID,
            'academy_id' => $academyID,
            'play_set_id' => $playsetID,
            'payment_mode' => $paymentMode
        ]);
    }
}
