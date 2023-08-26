<?php


namespace App\Repositories;


use App\Models\FcmToken;
use App\Repositories\Interfaces\FcmInterface;

class FcmRepository implements FcmInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param FcmToken $model
     */
    public function __construct(FcmToken $model)
    {
        $this->model = $model;
    }

    /**
     * Create A Token If Not Exist
     * @param array $data
     * @return mixed
     */
    public function createIfNotExist(array $data)
    {
        return $this->model->firstOrCreate($data);
    }

    /**
     * Delete Tokens
     * @param array $data
     * @return void
     */
    public function delete(array $data)
    {
        return $this->model->where($data)->delete();
    }
}
