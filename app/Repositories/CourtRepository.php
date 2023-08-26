<?php

namespace App\Repositories;

use App\Models\Court;
use App\Repositories\Interfaces\CourtInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CourtRepository implements CourtInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Court $model
     */
    public function __construct(Court $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All Courts
     * @param array $where
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(array $where = [], array $relationships = [])
    {
        return $this->model->when($where != [], function ($query) use ($where){
            $query->where($where);
        })->with($relationships)->get();
    }
}
