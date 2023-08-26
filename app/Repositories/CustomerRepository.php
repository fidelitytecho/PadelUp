<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository implements CustomerInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    /**
     * Create New Customer
     * @param array $data
     * @return mixed
     */
    public function all(array $where = [], array $relationships = [])
    {
        return $this->model->when($where != [], function ($query) use ($where){
            $query->where($where);
        })->with($relationships)->get();
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    /**
     * Find Category By ID
     * @param int $id
     * @param array $relationships
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function findByID(int $id, array $relationships = [])
    {
        return $this->model->with($relationships)->find($id);
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
