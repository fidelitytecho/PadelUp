<?php


namespace App\Repositories\Interfaces;

interface PaymentInterface
{
    /**
     * All Payments
     * @return mixed
     */
    public function all();

    /**
     * Create Booking Payment
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update Booking Payment
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * Find Booking Payment
     * @param int $id
     * @return mixed
     */
    public function findByID(int $id);
}
