<?php


namespace App\Repositories\Interfaces;

interface PurchaseAttemptInterface
{
    /**
     * Create Purchase Attempt
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * Find Purchase Attempt
     * @param array $data
     * @return mixed
     */
    public function findFirstWhere(array $data);

    /**
     * Find Purchase Attempts
     * @param array $data
     * @return mixed
     */
    public function find(array $data);
}
