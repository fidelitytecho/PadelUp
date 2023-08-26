<?php


namespace App\Repositories\admin\Interfaces\Bookings;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface A_AllBookingsInterface
{
    /**
     * Fetch All Bookings
     * @param string $date
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(string $date, array $relationships = []);
}
