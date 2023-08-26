<?php

namespace App\Http\Controllers\api\v1\mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewBookingRequest;
use App\Services\BookService;
use App\Services\mobile\bookings\CancelBookingService;
use App\Services\mobile\bookings\RetrieveBookingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class M_BookingController extends Controller
{
    private $bookService, $retrieveBookingsService, $cancelBookingService;

    /**
     * Create a new instance.
     * @param BookService $bookService
     * @param RetrieveBookingsService $retrieveBookingsService
     * @param CancelBookingService $cancelBookingService
     */
    public function __construct(BookService $bookService, RetrieveBookingsService $retrieveBookingsService, CancelBookingService $cancelBookingService)
    {
        $this->bookService = $bookService;
        $this->retrieveBookingsService = $retrieveBookingsService;
        $this->cancelBookingService = $cancelBookingService;
    }

    /**
     * Retrieve All Bookings Related to the logged in customer
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->retrieveBookingsService->retrieveCustomerBookings();
    }

    /**
     * Store a newly created booking
     *
     * @param NewBookingRequest $request
     * @return JsonResponse
     */
    public function store(NewBookingRequest $request): JsonResponse
    {
        return $this->bookService->book($request->validated());
    }

    /**
     * Cancel Booking Function
     *
     * @param int $bookingID
     * @return JsonResponse
     */
    public function cancelBooking(int $bookingID): JsonResponse
    {
        return $this->cancelBookingService->cancelBooking($bookingID);
    }
}
