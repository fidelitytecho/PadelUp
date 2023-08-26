<?php

namespace App\Services;

use App\Notifications\SendOTP;
use Illuminate\Support\Facades\Notification;

class SendOtpService
{
    /**
     * Send OTP
     *
     * @param string $mobile
     * @param string $message
     * @return void
     */
    public function SendOtpFunction(string $mobile, string $message)
    {
        Notification::send($mobile, new SendOTP($message));
    }
}
