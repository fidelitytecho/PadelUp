<?php

namespace App\Notifications;

use App\Broadcasting\sms\MailChimp;
use App\Broadcasting\Sms\SmsContent;
use App\Broadcasting\Sms\SmsMisr\SmsMisrChannel;
use App\Broadcasting\Sms\Twilio\SmsTwilioChannel;
use App\Broadcasting\Sms\Twilio\TwilioChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOTP extends Notification implements ShouldQueue
{
    use Queueable;

    public $otp;

    /**
     * Create a new notification instance.
     *
     * @param string $otp
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
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
        // array_push($viaCollection, !substr($notifiable, 0, 3) == '+20' ? 'mail' : SmsTwilioChannel::class);
        array_push($viaCollection, substr($notifiable, 0) == '+' ? TwilioChannel::class : 'mail');
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
        return (new SmsContent)->to($notifiable)->message('Your Padel Up otp is: '.$this->otp.'/n Otp Expires in 30 minutes');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Your Padel Up otp is: '.$this->otp.'.')
                    ->line('Otp Expires in 30 minutes.')
                    ->line('Thanks Padel.');
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
