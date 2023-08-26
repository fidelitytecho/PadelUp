<?php

namespace App\Repositories;

use App\Models\Players;
use App\Repositories\Interfaces\PlayerInterface;

class PlayerRepository implements PlayerInterface
{
    private $model;

    public function __construct(Players $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        $this->model->create($data);
    }
}
