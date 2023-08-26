<?php


namespace App\Repositories\admin\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface A_CreateEventInterface
{
    /**
     * Create Event
     * @param array $data
     * @return Builder[]|Collection
     */
    public function create(array $data);
}
