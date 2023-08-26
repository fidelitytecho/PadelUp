<?php

namespace App\Repositories;

use App\Models\Merch;
use App\Repositories\Interfaces\ProductInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Merch $model
     */
    public function __construct(Merch $model)
    {
        $this->model = $model;
    }

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
    /**
     * Find Category By ID
     * @param int $id
     * @param array $relationships
     * @return Builder|Builder[]|Collection|Model|null
     */
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
