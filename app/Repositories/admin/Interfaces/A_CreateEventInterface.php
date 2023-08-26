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
    public function all(array $where = [], array $relationships = []);
    public function findByID(int $id, array $relationships = []);
    public function update(int $id, array $data);
    public function delete(int $id);
}
