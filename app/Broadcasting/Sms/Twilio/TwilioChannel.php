<?php

namespace App\Broadcasting\Sms\Twilio;

use App\Models\User;

use Illuminate\Notifications\Notification;
use App\Broadcasting\Sms\SmsContent;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

class TwilioChannel
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
        //if can send sms
        $account_sid = config('app.TWILIO_SID');
        $auth_token = config('app.TWILIO_AUTH_TOKEN');
        $twilio_number = "+15017122661";

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $data->to,
            [
                'from' => $twilio_number,
                'body' => $data->message
            ]
        );
    }
}
