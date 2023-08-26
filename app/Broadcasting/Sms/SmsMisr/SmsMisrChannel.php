<?php

namespace App\Broadcasting\Sms\SmsMisr;

use App\Models\User;

use Illuminate\Notifications\Notification;
use App\Broadcasting\Sms\SmsContent;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsMisrChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param User $user
     * @return void
     */
    public function join(User $user)
    {
        //
    }

    public function send($notifiable, Notification $notification)
    {

        $data = $notification->toSms($notifiable);

        $test = Http::withHeaders([
            "Accept" => "application/json",
            "content-type" => "application/json"
        ])->post('https://smsmisr.com/api/vSMS/?Username=' . config('app.SMS_MISR_USERNAME') . '&password=' . config('app.SMS_MISR_PASSWORD') . '&Msignature=' . config('app.SMS_MISR_M_SIGNATURE') . '&Token=' . config('app.SMS_MISR_TOKEN') . '&Mobile=' . $data->to . '&Code=' . $data->message . '&language=1', ['verify' => false]);
        dd($test);
    }
}
