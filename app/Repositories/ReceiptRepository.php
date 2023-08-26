<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ReceiptInterface;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ReceiptRepository implements ReceiptInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Receipt $model
     */
    public function __construct(Receipt $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All Bookings
     *
     * @param int|null $customer_id
     * @param array $relationships
     * @param array $whereArray
     * @param bool|null $whereCancelled
     * @return Builder[]|Collection
     */
    public function all(int $customer_id = null, array $relationships = [], array $whereArray = [], bool $whereCancelled = null)
    {
        return $this->model->where($whereArray)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store Booking
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Find Booking
     * @param array $data
     * @return mixed
     */
    public function findFirstWhere(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * Find Booking By ID
     * @param int $id
     * @param array $relationships
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function findByID(int $id, array $relationships = [])
    {
        return $this->model->with($relationships)->findOrFail($id);
    }

    /**
     * Update Booking
     * @param int $bookingID
     * @param array $data
     * @return mixed
     */
    public function update(int $recieptID, array $data)
    {
        $item = $this->model->findOrFail($recieptID);
        $item->update($data);
        return $item;
    }
}
