<?php

namespace App\Services\admin;

use App\Http\Resources\admin\A_BookingResource;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\ServiceInterface;
use App\Traits\CheckBookingOverlapTrait;
use Illuminate\Http\JsonResponse;

class A_BookService
{
    use CheckBookingOverlapTrait;

    private $booking, $service;

    /**
     * Create a new instance.
     * @param BookingInterface $booking
     * @param ServiceInterface $service
     */
    public function __construct(BookingInterface $booking, ServiceInterface $service)
    {
        $this->booking = $booking;
        $this->service = $service;
    }

    /**
     * Create New Booking
     *
     * @param array $data
     * @return JsonResponse
     */
    public function book(array $data): JsonResponse
    {
        // Check If there's no overlapping
        if ($this->isBookingOverlap($data)) {
            $data['label'] = 'Cash payment';
            $data['status'] = true;
            $data['end_time'] = date('Y-m-d H:i:s', strtotime('+' . $data['duration'] . ' minutes', strtotime($data['start_time'])));

            // Create Booking
            $createdBooking = $this->createBooking($data);

            return response()->json([
                'success' => true,
                'message'=> 'Booking Created Successfully',
                'data'=> new A_BookingResource($createdBooking->load('Customer.User', 'Currency', 'Court')),
            ]);
        }
        $output = ([
            'success' => false,
            'message'=> 'Unfortunately the time slot you have selected has been taken. Please select a different court or timeslot'
        ]);
        return response()->json($output, 500);
    }

    /**
     * Check Booking Overlap Function
     * @param array $data
     * @return bool
     */
    public function isBookingOverlap(array $data): bool
    {
        $searchData = [
            'court_id' => $data['court_id'],
            'start_time' => date('Y-m-d H:i:s', strtotime($data['start_time'])),
            'end_time' => date('Y-m-d H:i:s', strtotime('+' . $data['duration'] . ' minutes', strtotime($data['start_time'])))
        ];

        return $this->checkBookingOverlap($searchData);
    }

    /**
     * Create Booking Function
     *
     * @param array $data
     * @return mixed
     */
    public function createBooking(array $data)
    {
        $serviceData = $this->service->findByID($data['service_id']);

        $data['cost'] = $serviceData->cost;
        $data['duration'] = $serviceData->duration;
        $data['start_time'] = date('Y-m-d H:i:s', strtotime($data['start_time']));

        return $this->booking->store($data);
    }
}
