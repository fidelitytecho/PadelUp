<?php

namespace App\Broadcasting\FCM;

use App\Models\FcmToken;
use App\Models\User;

use Illuminate\Notifications\Notification;
use Kreait\Firebase;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;

class FCMChannel
{
    protected $message;
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
        $data = $notification->toFCM();

        $messaging = (new Firebase\Factory())->withServiceAccount(config('app.FIREBASE_CREDENTIALS'))->createMessaging();

        $androidConfig = AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'title' => $data->title,
                'body' => $data->body,
                'sound' => 'default',
            ],
        ]);

        $iosConfig = ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'sound' => 'default',
                ],
            ],
        ]);

        $notificationArray = [
            'notification' => [
                'body'=> $data->body,
                'title'=> $data->title,
                'sound' => 'default',
                'image' => $data->image
            ],
            'data' => $data->data,
        ];

        if ($data->topic !== null) {
            $notificationArray['topic'] = $data->topic;
        }

        $message = CloudMessage::fromArray($notificationArray)->withAndroidConfig($androidConfig)->withApnsConfig($iosConfig);

        try {
            if ($data->topic !== null) {
                $messaging->send($message);
            }else {
                $messaging->sendMulticast($message, array_unique($data->tokens));
            }
        } catch (Firebase\Exception\MessagingException $e) {
        } catch (Firebase\Exception\FirebaseException $e) {
        }
    }
}
