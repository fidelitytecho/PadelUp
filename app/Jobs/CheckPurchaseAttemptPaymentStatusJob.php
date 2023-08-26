<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\PurchaseAttempt;
use App\Models\Receipt;
use App\Repositories\Interfaces\PurchaseAttemptInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckPurchaseAttemptPaymentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $purchaseAttempt;
    private $booking;
    private $receipt;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PurchaseAttempt $purchaseAttempt, Booking $booking = null, Receipt $receipt = null)
    {
        $this->purchaseAttempt = $purchaseAttempt;
        $this->booking = $booking;
        $this->receipt = $receipt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $purchaseAttempt = PurchaseAttempt::find($this->purchaseAttempt->id);

        if ($purchaseAttempt && $this->booking) {
            if (!$purchaseAttempt->payment_status && $purchaseAttempt->paymob_callback == null) {
                $this->booking->update([
                    'label' => 'Failed'
                ]);
            }
        }

        if ($purchaseAttempt && $this->receipt) {
            if (!$purchaseAttempt->payment_status && $purchaseAttempt->paymob_callback == null) {
                $this->receipt->update([
                    'label' => 'Failed'
                ]);
            }
        }

    }
}
