<?php


namespace App\Repositories\Interfaces;

interface PaymobInterface
{
    /**
     * We Accept Auth
     *
     * @param $priceAfterDiscount
     * @param String $createdOrderID
     * @param $userData
     * @return array
     */
    public function weAcceptPayingProcess($priceAfterDiscount, String $createdOrderID, $userData): array;
}
