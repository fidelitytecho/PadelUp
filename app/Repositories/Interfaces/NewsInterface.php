<?php


namespace App\Repositories\Interfaces;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

interface NewsInterface
{
    /**
     * Fetch All News
     * @return News[]|Collection
     */
    public function all();

    /**
     * Create New Notification
     * @param array $data
     */
    public function create(array $data);
}
