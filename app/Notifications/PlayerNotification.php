<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlayerNotification extends Notification
{
    use Queueable;
    private $court_name, $slug;

    /**
     * Create a new notification instance.
     */
    public function __construct($court_name, $slug)
    {
        $this->court_name = $court_name;
        $this->slug = $slug;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line("You've been invited to play in ".$this->court_name)
                    ->action('Notification Action', url('/'))
                    ->line('Thank you,PadelUp!');
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
