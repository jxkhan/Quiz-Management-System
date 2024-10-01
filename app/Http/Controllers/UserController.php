<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password; // For password reset



class UserController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:8|confirmed',
            'role' => 'required|in:admin,dev',
        ]);

        // Return errors if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a new user instance after a valid registration
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encrypt the password
        ]);
        $user->assignRole($request->role);

        // Return success response
        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user
        ], 201); // 201 status code for created
    }

    // User Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Attempt to log the user in
        if (!$token = Auth::attempt($validator->validated()))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the token after a successful login
       // return $this->respondWithToken($token);
       $user = Auth::user();
$role = $user->roles->pluck('name'); // Assuming 'name' is the field in the roles table

// Return the token and user roles after a successful login
return response()->json([
    'user' => [
        // 'id' => $user->id,
        // 'name' => $user->name,
        // 'email' => $user->email,
        'role' => $role, // Array of roles
    ],
    'access_token' => $token,
    'token_type' => 'bearer',
    'expires_in' => Auth::factory()->getTTL() * 60,
    
]);
    }

    // Respond with Token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60 // Get token time-to-live in seconds
        ]);
    }
    public function profile(){
        return response()->json(auth()->user())  ; 
    }
    public function logout(){
        auth()->logout();
        return response()->json(['message'=> 'user Loged out successfully'],200);
    }
    public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    if ($status == Password::PASSWORD_RESET) {
        return response()->json(['message' => 'Password reset successfully.'], 200);
    }

    return response()->json(['message' => 'Password reset failed.'], 500);
}

}