<?php


namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface BookingInterface
{
    /**
     * Fetch All Bookings
     * @param int|null $customer_id
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(int $customer_id = null, array $relationships = [], array $whereArray = [], bool $whereCancelled = null);

    /**
     * Store Booking
     * @param array $data
     */
    public function store(array $data);

    /**
     * Find Booking
     * @param array $data
     */
    public function findFirstWhere(array $data);

    /**
     * Find Booking By ID
     * @param int $id
     * @param array $relationships
     * @return mixed
     */
    public function findByID(int $id, array $relationships = []);

    /**
     * Update Booking
     * @param int $bookingID
     * @param array $data
     * @return mixed
     */
    public function update(int $bookingID, array $data);
}
