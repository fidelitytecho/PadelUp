<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestCallMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    private $mobile;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from(config('app.MAIL_FROM_ADDRESS'), config('app.name'));

        $this->subject('Padel Up Support');

        return $this->view('mail.request-call-mail')->with([
            'mobile' => $this->mobile
        ]);
    }
}
