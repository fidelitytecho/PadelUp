<?php


namespace App\Repositories\Interfaces;

interface PurchaseInterface
{
    /**
     * Create Payment Purchase
     * @param array $data
     * @return mixed
     */
    public function store(array $data);
}
