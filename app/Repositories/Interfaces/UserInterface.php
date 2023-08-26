<?php


namespace App\Repositories\Interfaces;

interface UserInterface
{
    /**
     * Create User
     * @param array $data
     */
    public function create(array $data);

    /**
     * Show User
     * @param array $data
     * @param string $role
     * @return mixed
     */
    public function show(array $data, string $role);

    /**
     * Find Users
     * @param array $data
     */
    public function find(array $data);

    /**
     * Find User By ID
     * @param int $user_id
     */
    public function findByID(int $user_id);

    /**
     * Update User
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * Delete User
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
