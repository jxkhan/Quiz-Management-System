<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendingStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // You can add authorization logic if needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pending_students',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }
}
