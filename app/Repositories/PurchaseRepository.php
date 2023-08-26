<?php

namespace App\Repositories;

use App\Models\Purchase;
use App\Repositories\Interfaces\PurchaseInterface;

class PurchaseRepository implements PurchaseInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Purchase $model
     */
    public function __construct(Purchase $model)
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
}
