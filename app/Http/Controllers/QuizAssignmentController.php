<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAssignment;

class QuizAssignmentController extends Controller
{
    public function assignQuizToStudent(Request $request)
    {
        $assignment = QuizAssignment::create([
            'quiz_id' => $request->quiz_id,
            'student_id' => $request->student_id,
            'assigned_by' => auth()->user()->id, // Manager/Supervisor ID
            'status' => 'pending',
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json(['message' => 'Quiz assigned to student!']);
    }

    public function getAssignedQuizzes($student_id)
    {
        $assignments = QuizAssignment::with('quiz')->where('student_id', $student_id)->get();
        return response()->json($assignments);
    }
}
