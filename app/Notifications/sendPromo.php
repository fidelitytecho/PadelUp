<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMChannel;
use App\Broadcasting\FCM\FCMContent;
use App\Broadcasting\Sms\SmsContent;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Str;

class sendPromo extends Notification
{
    use Queueable;
    private $dataObject, $message, $title, $tokens, $image, $topic, $slug;

    /**
     * Create a new notification instance.
     */
    public function __construct(String $title, String $message, int $id, String $image = null, String $topic = null, array $tokens = [], array $dataObject = [])
    {
        $this->dataObject = $dataObject;
        $this->title = $title;
        $this->tokens = $tokens;
        $this->image = $image;
        $this->topic = $topic;
        $p = Promo::create([
            'user_id' => $id,
            'discount' => config('app.discount'),
            'slug' => Str::random(10),
            'expire' => Carbon::now()->addMonth()
        ]);
        $this->message = $message.' '.$p->slug;
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

    /**
     * Summary of toSms
     * @param mixed $notifiable
     * @return \App\Broadcasting\Sms\SmsContent
     */
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
