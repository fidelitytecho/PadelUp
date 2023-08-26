<?php

namespace App\Repositories\admin\Bookings;

use App\Models\Booking;
use App\Repositories\admin\Interfaces\Bookings\A_AllBookingsInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class A_AllBookingsRepository implements A_AllBookingsInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Booking $model
     */
    public function __construct(Booking $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All Bookings
     *
     * @param string $date
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(string $date, array $relationships = [])
    {
        return $this->model->whereDate('start_time', date('Y-m-d', strtotime($date)))
            ->with($relationships)->orderBy('start_time', 'asc')->get();
    }
}
