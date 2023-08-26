<?php

namespace App\Repositories;

use App\Models\PurchaseAttempt;
use App\Repositories\Interfaces\PurchaseAttemptInterface;

class PurchaseAttemptRepository implements PurchaseAttemptInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param PurchaseAttempt $model
     */
    public function __construct(PurchaseAttempt $model)
    {
        $this->model = $model;
    }

    /**
     * Create Payment Purchase
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find Purchase Attempt
     * @param array $data
     * @return mixed
     */
    public function findFirstWhere(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * Find Purchase Attempts
     * @param array $data
     * @return mixed
     */
    public function find(array $data)
    {
        return $this->model->where($data)->get();
    }
}
