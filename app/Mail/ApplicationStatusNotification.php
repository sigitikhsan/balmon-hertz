<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $emailSubject;
    public $body;

    public function __construct(string $emailSubject, string $body)
    {
        $this->emailSubject = $emailSubject;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->text('emails.application-status');
    }
}
