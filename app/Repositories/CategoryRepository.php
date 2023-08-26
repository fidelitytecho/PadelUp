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
}
