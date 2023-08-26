<?php

namespace App\Services\admin;

use App\Http\Resources\admin\A_BookingResource;
use App\Repositories\admin\Interfaces\Bookings\A_AllBookingsInterface;
use App\Repositories\Interfaces\BookingInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class A_AllBookingsService
{
    private $booking;

    /**
     * Create a new instance.
     * @param A_AllBookingsInterface $booking
     */
    public function __construct(A_AllBookingsInterface $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $date
     * @return array
     */
    public function allBookings(string $date): array
    {
        $returnedBookings = [];
        for ($i=0; $i<=6; $i++) {
            $nextDay = date('Y-m-d', strtotime('+' . $i . ' days', strtotime($date)));
            $bookings = $this->booking->all(
                $nextDay,
                ['Customer.User', 'Currency', 'Court', 'Payments' => function($q) {
                    $q->whereHas('Purchases', function ($q) {
                        $q->whereHas('PurchaseAttempt', function ($q) {
                            $q->where('payment_status', true);
                        });
                    });
                }, 'Payments.Purchases' => function($q) {
                    $q->whereHas('PurchaseAttempt', function ($q) {
                        $q->where('payment_status', true);
                    });
                }, 'Payments.PaymentMode']
            );
            if($bookings->count() > 0) {
                foreach ($bookings as $booking) {
                    array_push($returnedBookings, new A_BookingResource($booking));
                }
            }else {
                array_push($returnedBookings, [
                    'startDate' => $nextDay,
                ]);
            }
        }
        return [
            'data' => $returnedBookings
        ];
    }
}
