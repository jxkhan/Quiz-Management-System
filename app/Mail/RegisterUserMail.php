<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class RegisterUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Set Your Password',
        );
    }

    public function build()
    {
        return $this->subject('Set Up Your Password')
            ->view('emails.registeruser'); // Email content
    }
}
