<?php

namespace App\Services;

use App\Models\User;
use App\Models\PendingStudent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use App\Mail\RegisterUserMail;
use App\Mail\AcceptStudentMail;
use App\Mail\RejectStudentMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminService
{
    // Register a manager
    public function registerManager($request)
    {
        return $this->registerUser($request, 'manager');
    }

    // Register a supervisor
    public function registerSupervisor($request)
    {
        return $this->registerUser($request, 'supervisor');
    }

    // Common logic for registering a user
    protected function registerUser($request, $role)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(10)), // Generate a random password
        ]);

        // Assign role
        $role = Role::where('name', $role)->first();
        if ($role) {
            $user->assignRole($role);
        }

        // Generate token for password reset
        $token = Str::random(64);

        // Insert or update the password reset token manually
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send email with token
        try {
            Mail::to($user->email)->send(new RegisterUserMail($user, $token));
        } catch (\Exception $e) {
            Log::error('Error sending registration email: ' . $e->getMessage());
            return ['message' => 'Error sending email.', 'status_code' => 500];
        }

        return ['message' => ucfirst($role->name) . ' registered successfully. Check email to set password.', 'status_code' => 201];
    }

    // Accept a student
    public function acceptStudent($id)
    {
        $pendingStudent = PendingStudent::findOrFail($id);

        if (User::where('email', $pendingStudent->email)->exists()) {
            return ['message' => 'Email already exists in users.', 'status_code' => 400];
        }

        // Accept the student
        $pendingStudent->status = 'accepted';
        $pendingStudent->save();

        // Move student to the users table
        $user = User::create([
            'name' => $pendingStudent->name,
            'email' => $pendingStudent->email,
            'password' => Hash::make(Str::random(10)),
        ]);

        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            $user->assignRole($studentRole);
        }

        // Generate token for password reset
        $token = Str::random(64);

        // Insert or update the password reset token manually
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send acceptance email
        try {
            Mail::to($pendingStudent->email)->send(new AcceptStudentMail($pendingStudent, $token));
        } catch (\Exception $e) {
            Log::error('Error sending acceptance email: ' . $e->getMessage());
        }

        return ['message' => 'Student approved successfully. Check email to set password.', 'status_code' => 200];
    }

    // Reject a student
    public function rejectStudent($id)
    {
        $pendingStudent = PendingStudent::findOrFail($id);
        $pendingStudent->status = 'rejected';
        $pendingStudent->save();

        // Send rejection email
        try {
            Mail::to($pendingStudent->email)->send(new RejectStudentMail($pendingStudent));
        } catch (\Exception $e) {
            Log::error('Error sending rejection email: ' . $e->getMessage());
            return ['message' => 'Error sending rejection email.', 'status_code' => 500];
        }

        return ['message' => 'Student rejected successfully and email sent.', 'status_code' => 200];
    }
}
