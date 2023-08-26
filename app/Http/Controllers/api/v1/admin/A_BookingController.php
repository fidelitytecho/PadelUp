<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\A_BookRequest;
use App\Http\Resources\admin\A_BookingResource;
use App\Http\Resources\app\BookingsResource;
use App\Repositories\Interfaces\BookingInterface;
use App\Services\admin\A_AllBookingsService;
use App\Services\admin\A_BookService;
use App\Services\admin\A_ShowSingleBookingService;
use App\Services\BookService;
use App\Services\mobile\bookings\CancelBookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class A_BookingController extends Controller
{
    private $bookService;
    private $allBookingsService;
    private $showSingleBookingService;
    private $cancelBookingService;

    /**
     * Create a new instance.
     * @param A_BookService $bookService
     * @param A_AllBookingsService $allBookingsService
     * @param A_ShowSingleBookingService $showSingleBookingService
     * @param CancelBookingService $cancelBookingService
     */
    public function __construct(
        A_BookService $bookService, A_AllBookingsService $allBookingsService,
        A_ShowSingleBookingService $showSingleBookingService, CancelBookingService $cancelBookingService)
    {
        $this->bookService = $bookService;
        $this->allBookingsService = $allBookingsService;
        $this->showSingleBookingService = $showSingleBookingService;
        $this->cancelBookingService = $cancelBookingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $date
     * @return array
     */
    public function index(string $date): array
    {
        return $this->allBookingsService->allBookings($date);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param A_BookRequest $request
     * @return JsonResponse
     */
    public function store(A_BookRequest $request): JsonResponse
    {
        return $this->bookService->book($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return A_BookingResource
     */
    public function show(int $id): A_BookingResource
    {
        return $this->showSingleBookingService->showBooking($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $bookingID
     * @return JsonResponse
     */
    public function destroy($bookingID): JsonResponse
    {
        return $this->cancelBookingService->cancelBooking($bookingID);
    }
}
