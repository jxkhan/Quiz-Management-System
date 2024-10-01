<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_assignment_id', 'student_id', 'submitted_at', 'result', 'recording_path'];

    public function quizAssignment()
    {
        return $this->belongsTo(QuizAssignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
