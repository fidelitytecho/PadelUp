<?php


namespace App\Repositories\Interfaces;

interface FcmInterface
{
    /**
     * Create A Token If Not Exist
     * @param array $data
     * @return mixed
     */
    public function createIfNotExist(array $data);

    /**
     * Delete User Token
     * @param array $data
     * @return void
     */
    public function delete(array $data);
}
