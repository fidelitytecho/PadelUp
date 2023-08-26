<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\PurchaseAttempt;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\PaymentInterface;
use App\Repositories\Interfaces\PurchaseAttemptInterface;
use App\Repositories\Interfaces\PurchaseInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use App\Traits\InsertToWalletTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymobController extends Controller
{
    private $purchaseAttempt;
    private $walletHistory;

    use InsertToWalletTrait;

    /**
     * Create a new instance.
     * @param PurchaseAttemptInterface $purchaseAttempt
     * @param WalletHistoryInterface $walletHistory
     */
    public function __construct(PurchaseAttemptInterface $purchaseAttempt, WalletHistoryInterface $walletHistory)
    {
        $this->purchaseAttempt = $purchaseAttempt;
        $this->walletHistory = $walletHistory;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        Storage::disk('local')->put('weaccept1.txt', $request);
        if (isset($request['obj']['order']['id'])) {
            $purchaseAttempts = $this->purchaseAttempt->find([
                'paymob_order_id' => $request['obj']['order']['id'],
            ]);

            if ($request['obj']['success'] == 'true') {
                if ($purchaseAttempts) {
                    foreach ($purchaseAttempts as $purchaseAttempt) {
                        // Update Booking Row
                        $bookingUpdateArray = [
                            'payment_status' => true,
                            'paymob_callback' => $request
                        ];
                        $purchaseAttempt->Purchase->Payment->Booking->update([
                            'label' => 'Confirmed'
                        ]);

                        if ($purchaseAttempt->Purchase->payment_mode_id == 1) {
                            $this->walletHistory->store([
                                'customer_id' => $purchaseAttempt->Purchase->customer_id,
                                'amount' => (double)$purchaseAttempt->Purchase->amount
                            ]);
                        }

                        $purchaseAttempt->update($bookingUpdateArray);
                    }
                }
            }else {
                $bookingUpdateArray = [
                    'paymob_callback' => $request
                ];

                foreach ($purchaseAttempts as $purchaseAttempt) {
                    $purchaseAttempt->Purchase->Payment->Booking->update([
                        'label' => 'Failed'
                    ]);
                    $purchaseAttempt->update($bookingUpdateArray);
                }
            }
        }
    }
}
