<?php

namespace App\Services\auth;

use App\Repositories\Interfaces\FcmInterface;

class SaveFcmTokenService
{
    private $fcmToken;

    /**
     * Create a new instance.
     */
    public function __construct(FcmInterface $fcmToken)
    {
        $this->fcmToken = $fcmToken;
    }

    /**
     * Save Firebase Token
     * @param string $fcmToken
     * @param int $userID
     */
    public function saveToken(string $fcmToken, int $userID)
    {
        $this->fcmToken->delete([
            'user_id' => $userID,
            'token' => $fcmToken
        ]);
        $this->fcmToken->createIfNotExist([
            'user_id' => $userID,
            'token' => $fcmToken
        ]);
    }
}
