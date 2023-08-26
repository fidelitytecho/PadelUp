<?php

namespace App\Traits;

use App\Repositories\Interfaces\WalletHistoryInterface;

trait InsertToWalletTrait
{
    private $walletHistory;

    /**
     * Create a new instance.
     * @param WalletHistoryInterface $walletHistory
     */
    public function __construct(WalletHistoryInterface $walletHistory)
    {
        $this->walletHistory = $walletHistory;
    }

    /**
     * Insert to wallet
     * @param int $customerID
     * @param float $amount
     * @return mixed
     */
    public function insertToWallet(int $customerID, float $amount)
    {
        return $this->walletHistory->store([
            'customer_id' => $customerID,
            'amount' => $amount
        ]);
    }
}
