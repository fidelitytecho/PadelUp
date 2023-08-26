<?php

namespace App\Observers;

use App\Models\Booking;
use Illuminate\Cache\CacheManager;

class BookingObserver
{
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Handle the Booking "created" event.
     *
     * @param Booking $booking
     * @return void
     */
    public function created(Booking $booking)
    {
        $this->cacheManager->forget('customerBookings' . $booking->customer_id);
    }

    /**
     * Handle the Booking "updated" event.
     *
     * @param Booking $booking
     * @return void
     */
    public function updated(Booking $booking)
    {
        $this->cacheManager->forget('customerBookings' . $booking->customer_id);
    }
}
