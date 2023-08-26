<?php


namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ServiceInterface
{
    /**
     * Fetch All Services
     * @param array $where
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(array $where = [], array $relationships = []);


    /**
     * Find Service By ID
     * @param int $id
     */
    public function findByID(int $id);
}
