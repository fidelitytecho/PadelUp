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
}
