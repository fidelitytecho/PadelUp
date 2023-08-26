<?php

namespace App\Services\mobile\bookings;

use App\Http\Resources\app\BookingsResource;
use App\Repositories\Interfaces\BookingInterface;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class RetrieveBookingsService
{
    private $booking, $cacheManager;

    const TTL = 1440; // 1 DAY

    /**
     * Create a new instance.
     * @param BookingInterface $booking
     * @param CacheManager $cacheManager
     */
    public function __construct(BookingInterface $booking, CacheManager $cacheManager)
    {
        $this->booking = $booking;
        $this->cacheManager = $cacheManager;
    }

    /**
     * Retrieve Customer Bookings
     *
     * @return JsonResponse
     */
    public function retrieveCustomerBookings(): JsonResponse
    {
        // Retrieve All Customer Bookings With Caching
        $customerBookings = $this->retrieveAllCustomerBookingsWithCaching();

        // Output Array
        $output = ([
            'upcoming' => BookingsResource::collection($customerBookings->where('end_time', '>', date('Y-m-d H:i:s'))->whereNotIn('label', ['Cancelled', 'Expired', 'Failed'])),
            'previous' => BookingsResource::collection($customerBookings->where('end_time', '<', date('Y-m-d H:i:s'))->whereNotIn('label', ['Cancelled', 'Expired', 'Failed'])),
            'cancelled' => BookingsResource::collection($customerBookings->whereIn('label', ['Cancelled', 'Expired', 'Failed']))
        ]);

        return response()->json($output);
    }

    /**
     * Retrieve All Customer Bookings With Caching
     *
     * @param array $whereArray
     * @return Builder[]|Collection
     */
    public function retrieveAllCustomerBookingsWithCaching(array $whereArray = [])
    {
        return $this->booking->all(
            auth('api')->user()->load('Customer')->Customer->id,
            ['Court', 'Currency', 'Service', 'Purchases.PurchaseAttempt'], $whereArray);
    }
}
