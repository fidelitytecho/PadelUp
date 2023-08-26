<?php

namespace App\Broadcasting\Sms\Twilio;

use App\Models\User;

use Illuminate\Notifications\Notification;
use App\Broadcasting\Sms\SmsContent;
use Illuminate\Support\Facades\Http;

class SmsTwilioChannel
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
        $test = Http::asForm()->withBasicAuth(config('app.TWILIO_SID'), config('app.TWILIO_AUTH_TOKEN'))
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->post('https://verify.twilio.com/v2/Services/'. config('app.SERVICE_SID') .'/Verifications', [
                'Locale' => 'en',
                'CustomCode' => $data->message,
                'To' => $data->to,
                'Channel' => 'sms'
            ]);
        dd($test);
    }
}
