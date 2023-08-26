<?php


namespace App\Repositories\Interfaces;

interface CustomerInterface
{
    /**
     * Create New Customer
     * @param array $data
     */
    public function create(array $data);
}
