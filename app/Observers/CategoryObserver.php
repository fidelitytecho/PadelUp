<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Cache\CacheManager;

class CategoryObserver
{
    private $cacheManager;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Handle the Category "created" event.
     *
     * @param Category $category
     * @return void
     */
    public function created(Category $category)
    {
        $this->cacheManager->forget('Category' . $category->id);
    }

    /**
     * Handle the Category "updated" event.
     *
     * @param Category $category
     * @return void
     */
    public function updated(Category $category)
    {
        $this->cacheManager->forget('Category' . $category->id);
    }
}
