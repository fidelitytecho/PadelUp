<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository implements CategoryInterface
{
    private $model, $cacheManager;

    const TTL = 1440; // 1 DAY

    /**
     * Create a new instance.
     *
     * @param Category $model
     * @param CacheManager $cacheManager
     */
    public function __construct(Category $model, CacheManager $cacheManager)
    {
        $this->model = $model;
        $this->cacheManager = $cacheManager;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function createRelationship(int $id, array $image = [], array $review = [])
    {
        $item = $this->model->findOrFail($id);
        $item->when($image != [], function ($query) use ($item, $image){
            $item->Images()->create($image);
        });
        $item->when($review != [], function ($query) use ($item, $review){
            $item->Reviews()->create($review);
        });
        return $item->with([$image ? 'Images': '', $review ? 'Review': '']);
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
        return $this->cacheManager->remember('Category' . $id, self::TTL, function () use($relationships, $id){
            return $this->model->with($relationships)->find($id);
        });
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
