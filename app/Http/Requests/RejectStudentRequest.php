<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:pending_students,id', // Assuming you have a 'pending_students' table
        ];
    }
}
