<?php


namespace App\Repositories;


use App\Models\News;
use App\Repositories\Interfaces\NewsInterface;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository implements NewsInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param News $model
     */
    public function __construct(News $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch All News
     * @return News[]|Collection
     */
    public function all()
    {
        return $this->model->latest()->get();
    }

    /**
     * Create New Notification
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
