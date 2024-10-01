<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class AcceptStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $token;

    public function __construct($student, $token)
    {
        $this->student = $student;
        $this->token = $token;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Application has been Accepted',
        );
    }

    public function build()
    {
        return $this->subject('Your Application has been Accepted')
            ->view('emails.acceptstudent');
    }
}
