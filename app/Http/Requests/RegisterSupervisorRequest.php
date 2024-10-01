<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSupervisorRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,supervisor',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
        ];
    }
}
