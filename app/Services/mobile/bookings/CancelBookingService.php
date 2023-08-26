<?php

namespace App\Services\mobile\bookings;

use App\Http\Resources\app\BookingsResource;
use App\Repositories\Interfaces\BookingInterface;
use App\Repositories\Interfaces\WalletHistoryInterface;
use Illuminate\Http\JsonResponse;

class CancelBookingService
{
    private $booking;
    private $walletHistory;

    /**
     * Create a new instance.
     * @param BookingInterface $booking
     * @param WalletHistoryInterface $walletHistory
     */
    public function __construct(BookingInterface $booking, WalletHistoryInterface $walletHistory)
    {
        $this->booking = $booking;
        $this->walletHistory = $walletHistory;
    }

    /**
     * Fetch Category Data
     * @param int $id
     * @return JsonResponse
     */
    public function cancelBooking(int $id): JsonResponse
    {
        \DB::beginTransaction();
        try {

            // Fetch Booking Data
            $bookingData = $this->booking->findByID($id);

            if(!in_array($bookingData->label, ['Cancelled', 'Failed', 'Expired'])) {
                // Insert to Wallet where not cash
                if($bookingData->label === 'Confirmed') {
                    $this->walletHistory->store([
                        'customer_id' => $bookingData->customer_id,
                        'amount' => (double)$bookingData->cost
                    ]);
                }

                // Mark Booking As Cancelled
                $cancelledBooking = $this->booking->update($id, [
                    'label' => 'Cancelled'
                ]);

                \DB::commit();

                return response()->json([
                    'success' => true,
                    'message'=> 'Cancelled Successfully',
                    'data'=> new BookingsResource($cancelledBooking),
                ]);
            }

            return response()->json([
                'success' => false,
                'message'=> 'The booking is already cancelled or failed',
            ], 400);

        } catch (\Exception $ex) {
            \DB::rollback();
            $output = ([
                'success' => false,
                'message'=> 'Unable to Cancel The Booking'
            ]);
            return response()->json($output, 500);
        }
    }
}
