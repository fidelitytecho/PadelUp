<?php


namespace App\Repositories\Interfaces;

interface CategoryInterface
{
    /**
     * Find Category By ID
     * @param int $id
     * @param array $relationships
     */
    public function findByID(int $id, array $relationships = []);
    public function createRelationship(int $id, array $image = [], array $review = []);
    public function create(array $data);
    public function all(array $where = [], array $relationships = []);
    public function update(int $id, array $data);
    public function delete(int $id);
}
