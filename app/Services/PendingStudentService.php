<?php

namespace App\Services;

use App\Models\PendingStudent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PendingStudentService
{
    public function register($data)
    {
        // Store CV file locally and get the file path
        $cvPath = $data['cv_file']->store('cvs', 'public');

        // Create a pending student record
        $pendingStudent = PendingStudent::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'cv_path' => $cvPath,
        ]);
        // dd('working');

        // Send confirmation email to student
       $this->sendConfirmationEmail($pendingStudent);
      
        return $pendingStudent;
    }

    protected function sendConfirmationEmail($pendingStudent)
    {
        try {
            Mail::raw('Your registration has been submitted successfully and is pending admin approval.', function ($message) use ($pendingStudent) {
                $message->to($pendingStudent->email)
                        ->subject('Registration Submitted Successfully');
            });
            Log::info('Email sent successfully to ' . $pendingStudent->email);
        } catch (\Exception $e) {
            Log::error('Error sending email to ' . $pendingStudent->email . ': ' . $e->getMessage());
            throw $e; // Rethrow exception for handling in the controller
        }
        
    }
    public function getAllPendingStudents()
    {
        // Fetch all pending students from the database
        return PendingStudent::where(['id','name','email']);
    }
}
