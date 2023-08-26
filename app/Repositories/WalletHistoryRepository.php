<?php

namespace App\Repositories;

use App\Models\WalletHistory;
use App\Repositories\Interfaces\WalletHistoryInterface;

class WalletHistoryRepository implements WalletHistoryInterface
{
    private $model;

    /**
     * Create a new instance.
     *
     * @param WalletHistory $model
     */
    public function __construct(WalletHistory $model)
    {
        $this->model = $model;
    }

    /**
     * Create Wallet Record
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }
}
