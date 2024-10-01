<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\PendingStudent;

class StudentSubmittedNotification extends Notification
{
    use Queueable;

    protected $student;

    public function __construct(PendingStudent $student)
    {
        $this->student = $student;
    }

    // Send via database
    public function via($notifiable)
    {
        return ['database']; // Store in the database for dashboard notification
    }

    // Data stored in the database
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new student registration is pending approval.',
            'student_name' => $this->student->name,
            'student_email' => $this->student->email,
            'student_id' => $this->student->id,
        ];
    }
}
