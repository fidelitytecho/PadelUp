<?php


namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface CourtInterface
{
    /**
     * Fetch All Courts
     * @param array $where
     * @param array $relationships
     * @return Builder[]|Collection
     */
    public function all(array $where = [], array $relationships = []);
    public function findByID(int $id, array $relationships = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
