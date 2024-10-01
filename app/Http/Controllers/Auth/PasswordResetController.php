<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    // Send reset link to the user's email
    public function sendResetLinkEmail(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Send reset link to the email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }

    // Reset the password
    public function reset(Request $request)
    {
        // Validate request
        $request->validate([
            // 'email' => 'required|email|exists:users,email',
            // 'token' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'], 200)
            : response()->json(['message' => 'Password reset failed.'], 400);
    }
}
