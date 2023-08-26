<?php


namespace App\Repositories\Interfaces;

interface WalletHistoryInterface
{
    /**
     * Create Wallet Record
     * @param array $data
     * @return mixed
     */
    public function store(array $data);
}
