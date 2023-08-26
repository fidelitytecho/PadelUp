<?php


namespace App\Repositories;


use App\Models\Service;
use App\Models\User;
use App\Repositories\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Service $model
     */
    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All Services
     * @param array $where
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(array $where = [], array $relationships = [])
    {
        return $this->model->when($where != [], function ($query) use ($where){
            $query->where($where);
        })->with($relationships)->orderBy('duration')->get();
    }

    /**
     * Find Service By ID
     * @param int $id
     * @return mixed
     */
    public function findByID(int $id)
    {
        return $this->model->find($id);
    }
}
