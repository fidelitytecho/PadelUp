<?php

namespace App\Repositories\admin;

use App\Models\Booking;
use App\Models\Event;
use App\Repositories\admin\Interfaces\A_CreateEventInterface;
use App\Repositories\admin\Interfaces\Bookings\A_AllBookingsInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class A_CreateEventRepository implements A_CreateEventInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Event $model
     */
    public function __construct(Event $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All Bookings
     *
     * @param array $data
     * @return Builder[]|Collection
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    public function all(array $where = [], array $relationships = [])
    {
        return $this->model->when($where != [], function ($query) use ($where){
            $query->where($where);
        })->with($relationships)->get();
    }
    public function findByID(int $id, array $relationships = [])
    {
        return $this->model->with($relationships)->findOrFail($id);
    }
    public function update(int $id, array $data)
    {
        $item = $this->model->findOrFail($id);
        $item->update($data);
        return $item;
    }
    public function delete(int $id)
    {
        $item = $this->model->findOrFail($id);
        $item->delete();
        return $item ? 'Successful' : 'Failed';
    }
}
