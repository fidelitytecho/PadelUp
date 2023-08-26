<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\Interfaces\CompanyInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CompanyRepository implements CompanyInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param Company $model
     */
    public function __construct(Company $model)
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
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    /**
     * Find Category By ID
     * @param int $id
     * @param array $relationships
     * @return Builder|Builder[]|Collection|Model|null
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
