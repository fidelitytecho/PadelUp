<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\Interfaces\BookingInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BookingRepository implements BookingInterface
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
     * @param int|null $customer_id
     * @param array $relationships
     * @param array $whereArray
     * @param bool|null $whereCancelled
     * @return Builder[]|Collection
     */
    public function all(int $customer_id = null, array $relationships = [], array $whereArray = [], bool $whereCancelled = null)
    {
        return $this->model->when($customer_id != null, function ($query) use ($customer_id){
            $query->where('customer_id', $customer_id);
        })->where($whereArray)->with($relationships)->orderBy('created_at', 'desc')->get();
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
    public function update(int $bookingID, array $data)
    {
        $item = $this->model->findOrFail($bookingID);
        $item->update($data);
        return $item;
    }
}
