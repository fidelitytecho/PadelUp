<?php


namespace App\Repositories;


use App\Models\User;
use App\Repositories\Interfaces\UserInterface;

class UserRepository implements UserInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Register User
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Show User
     * @param array $data
     * @param string $role
     * @return mixed
     */
    public function show(array $data, string $role)
    {
        return $this->model->when($role !== null, function ($q) use($role){
            $q->role($role);
        })->where($data)->first();
    }

    /**
     * Find Users
     * @param array $data
     * @return mixed
     */
    public function find(array $data)
    {
        return $this->model->where($data)->get();
    }

    /**
     * Find User By ID
     * @param int $user_id
     * @return mixed
     */
    public function findByID(int $user_id)
    {
        return $this->model->find($user_id);
    }

    /**
     * Update User
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        $itemData = $this->model->find($id);
        $itemData->update($data);
        return $itemData;
    }

    /**
     * Delete User
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $userData = $this->findByID($id);
        return $userData->delete();
    }
}
