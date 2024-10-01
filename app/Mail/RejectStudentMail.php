<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class RejectStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    /**
     * Create a new message instance.
     *
     * @param $student
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Application was Rejected',
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Application was Rejected')
            ->view('emails.rejectstudent'); // Blade view for email content
    }
}
