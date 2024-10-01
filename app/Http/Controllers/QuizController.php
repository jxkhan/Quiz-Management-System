<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;

class QuizController extends Controller
{
    public function createQuiz(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:schedule_at',
        ]);

        // Create the quiz with schedule and expiration times
        $quiz = Quiz::create([
            'name' => $request->name,
            'description' => $request->description,
            'schedule_at' => $request->schedule_at, // Schedule date
            'expires_at' => $request->expires_at,   // Expiration date
            'created_by' => auth()->user()->id, // Admin ID
        ]);

        return response()->json(['message' => 'Quiz created successfully!']);
    }

    public function getAllQuizzes()
    {
        $quizzes = Quiz::with('questions')->get();
        return response()->json($quizzes);
    }
}
