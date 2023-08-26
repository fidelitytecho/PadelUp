<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMChannel;
use App\Broadcasting\FCM\FCMContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNewsNotification extends Notification
{
    use Queueable;
    private $dataObject, $message, $title, $tokens, $image, $topic;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(String $title, String $message, String $image, String $topic = null, array $tokens = [], array $dataObject = [])
    {
        (array)$this->dataObject = $dataObject;
        $this->title = $title;
        $this->message = $message;
        $this->tokens = $tokens;
        $this->image = $image;
        $this->topic = $topic;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $viaCollection = [];
        array_push($viaCollection, FCMChannel::class);
        return $viaCollection;
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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
