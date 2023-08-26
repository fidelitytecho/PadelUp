<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMContent;
use App\Broadcasting\Sms\SmsContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendWish extends Notification
{
    use Queueable;
    private $dataObject, $message, $title, $tokens, $image, $topic;

    /**
     * Create a new notification instance.
     */
    public function __construct(String $title, String $message, String $image = null, String $topic = null, array $tokens = [], array $dataObject = [])
    {
        $this->dataObject = (array)$dataObject;
        $this->title = $title;
        $this->message = $message;
        $this->tokens = $tokens;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $collection = [FCMChannel::class];
        array_push($collection, !substr($notifiable, 0, 3) == '+20' ? SmsMisrChannel::class : SmsTwilioChannel::class);
        return $collection;
    }

    public function toFcm()
    {
        $this->dataObject = json_encode(array_merge($this->dataObject, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ]));

        return (new FCMContent)
            ->title($this->title)
            ->body($this->message)
            ->to($this->tokens)
            ->topic($this->topic)
            ->image($this->image)
            ->data([
                'json' => $this->dataObject
            ]);
    }

    public function toSms($notifiable): SmsContent
    {
        return (new SmsContent)->to($notifiable)->message($this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
