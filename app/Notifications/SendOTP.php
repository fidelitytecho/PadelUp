<?php

namespace App\Notifications;

use App\Broadcasting\Sms\SmsContent;
use App\Broadcasting\Sms\SmsMisr\SmsMisrChannel;
use App\Broadcasting\Sms\Twilio\SmsTwilioChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTP extends Notification
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        $viaCollection = [];
        array_push($viaCollection, !substr($notifiable, 0, 3) == '+20' ? SmsMisrChannel::class : SmsTwilioChannel::class);
        return $viaCollection;
    }

    /**
     * Send Notification Using SMS
     *
     * @param  mixed  $notifiable
     * @return SmsContent
     */
    public function toSms($notifiable): SmsContent
    {
        return (new SmsContent)->to($notifiable)->message($this->message);
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
