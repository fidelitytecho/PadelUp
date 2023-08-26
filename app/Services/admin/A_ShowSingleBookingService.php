<?php

namespace App\Services\admin;

use App\Http\Resources\admin\A_BookingResource;
use App\Repositories\Interfaces\BookingInterface;

class A_ShowSingleBookingService
{
    private $booking;

    /**
     * Create a new instance.
     * @param BookingInterface $booking
     */
    public function __construct(BookingInterface $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $bookingID
     * @return A_BookingResource
     */
    public function showBooking(int $bookingID): A_BookingResource
    {
        return new A_BookingResource($this->booking->findByID($bookingID, ['Customer.User', 'Customer.Wallet', 'Service', 'Currency', 'Court', 'Payments.Purchases', 'Player']));

    }
}
