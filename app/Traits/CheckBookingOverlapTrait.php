<?php

namespace App\Traits;

use App\Models\Booking;
use App\Models\Event;

trait CheckBookingOverlapTrait
{
    /**
     * Check Booking Overlap Function
     * @param array $data
     * @return boolean
     */
    public function checkBookingOverlap(array $data): bool
    {
        $bookings = Booking::where('court_id', $data['court_id'])
            ->where('end_time', '>', $data['start_time'])
            ->where('start_time', '<', $data['end_time'])
            ->whereNotIn('label', ['Cancelled', 'Expired', 'Failed'])
            ->get();

        if (count($bookings) == 0) {
            $events = Event::where('court_id', $data['court_id'])
                ->where('end_time', '>', $data['start_time'])
                ->where('start_time', '<', $data['end_time'])
                ->get();
            if (count($events) == 0) {
                return true;
            }
        }
        return false;
    }
}
