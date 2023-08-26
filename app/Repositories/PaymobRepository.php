<?php

namespace App\Repositories;

use App\Repositories\Interfaces\PaymobInterface;
use Illuminate\Support\Facades\Http;

class PaymobRepository implements PaymobInterface
{
    /**
     * We Accept Auth
     *
     * @param $priceAfterDiscount
     * @param String $createdOrderID
     * @param array $userData
     * @return array
     */
    public function weAcceptPayingProcess($priceAfterDiscount, String $createdOrderID, $userData): array
    {
        $authResponse = $this->weAcceptAuth();
        $orderResponse = $this->weAcceptOrder($authResponse['token'], round($priceAfterDiscount), $createdOrderID);
        $weAcceptPaymentKey = $this->weAcceptPaymentKey(
            $authResponse['token'],
            $orderResponse['id'],
            round($priceAfterDiscount),
            $userData
        );

        return [
            'token' => $weAcceptPaymentKey['token'],
            'orderID' => $orderResponse['id']
        ];
    }

    /**
     * We Accept Auth
     *
     * @return array|mixed
     */
    private function weAcceptAuth()
    {
        $response = Http::withHeaders([
            "Accept" => "application/json",
        ])->post('https://accept.paymobsolutions.com/api/auth/tokens', [
            'api_key' => config('app.WE_ACCEPT_KEY')
        ]);

        return $response->json();
    }

    /**
     * We Accept Order
     *
     * @param $authToken
     * @param $orderPrice
     * @param $localOrderID
     * @return array|mixed
     */
    private function weAcceptOrder($authToken, $orderPrice, $localOrderID) {

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "content-type" => "application/json"
        ])->post('https://accept.paymobsolutions.com/api/ecommerce/orders', [
            "auth_token" => $authToken,
            "delivery_needed" => "false",
            "amount_cents" => $orderPrice * 100,
            "currency" => "EGP",
            "merchant_order_id" => $localOrderID,
            "items" => [
                0 => [
                    "name" => "Property",
                    "amount_cents" => $orderPrice * 100,
                    "description" => ".",
                    "quantity" => "1"
                ]
            ],
        ]);
        return $response->json();
    }

    /**
     * We Accept Payment Key
     *
     * @param $authToken
     * @param $weAcceptOrderID
     * @param $orderPrice
     * @param $userData
     * @return array|mixed
     */
    private function weAcceptPaymentKey($authToken, $weAcceptOrderID, $orderPrice, $userData) {

        $response = Http::withHeaders([
            "Accept" => "application/json",
            "content-type" => "application/json"
        ])->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
            "auth_token" => $authToken,
            "amount_cents" => $orderPrice * 100,
            "expiration" => config('app.WE_ACCEPT_EXPIRATION_ORDER_SECONDS'), // 10 min
            "order_id" => $weAcceptOrderID,
            "billing_data" => [
                "first_name" => $userData->first_name,
                "last_name"=> $userData->last_name,
                "email"=> $userData->email,
                "phone_number"=> $userData->full_mobile,
                "apartment"=> "NA",
                "floor"=> "NA",
                "street"=> "NA",
                "building"=> "NA",
                "shipping_method"=> "NA",
                "postal_code"=> "00000",
                "city"=> "NA",
                "country"=> "NA",
                "state"=> "NA"
            ],
            "currency"=> "EGP",
            "integration_id"=> config('app.WE_ACCEPT_INTEGRATION_ID'),
            "lock_order_when_paid"=> "True"
        ]);

        return $response->json();
    }

    /**
     * We Accept Capture Payments
     *
     * @param $transaction_id
     * @param $captureAmount
     * @return array|mixed
     */
    public function capturePayments($transaction_id, $captureAmount) {
        $authResponse = $this->weAcceptAuth();

        $response = Http::withHeaders([
            "Accept" => "application/json",
        ])->post('https://accept.paymobsolutions.com/api/acceptance/capture', [
            'auth_token' => $authResponse['token'],
            'transaction_id' => $transaction_id,
            'amount_cents' => $captureAmount * 100
        ]);

        return $response->json();
    }

    /**
     * We Accept Void Payments
     *
     * @param $transaction_id
     * @return array|mixed
     */
    public function voidPayments($transaction_id) {
        $authResponse = $this->weAcceptAuth();

        $response = Http::withHeaders([
            "Accept" => "application/json",
        ])->post('https://accept.paymobsolutions.com/api/acceptance/void_refund/void?token=' . $authResponse['token'], [
            'transaction_id' => $transaction_id,
        ]);

        return $response->json();
    }

    /**
     * We Accept Refund Payments
     *
     * @param $transaction_id
     * @param $captureAmount
     * @return array|mixed
     */
    public function refundPayments($transaction_id, $captureAmount) {
        $authResponse = $this->weAcceptAuth();

        $response = Http::withHeaders([
            "Accept" => "application/json",
        ])->post('https://accept.paymobsolutions.com/api/acceptance/capture', [
            'auth_token' => $authResponse['token'],
            'transaction_id' => $transaction_id,
            'amount_cents' => $captureAmount * 100
        ]);

        return $response->json();
    }
}
