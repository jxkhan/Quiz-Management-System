<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Middleware to allow only admin
    public function __construct()
    {
        $this->middleware('role:admin'); // This ensures only users with the 'admin' role can access these actions
    }

    // Create a new question for a quiz
    public function store(Request $request, $quiz_id)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'nullable|array', // Options only required for multiple choice
            'answer' => 'required|string'
        ]);

        $quiz = Quiz::findOrFail($quiz_id); // Find quiz by ID or fail if not found

        // Store the new question
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'options' => isset($validated['options']) ? json_encode($validated['options']) : null,
            'answer' => $validated['answer']
        ]);

        return response()->json(['message' => 'Question created successfully!', 'question' => $question], 201);
    }

    // Fetch all questions for a quiz
    public function index($quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $questions = $quiz->questions;

        return response()->json(['questions' => $questions]);
    }

    // Delete a question
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully']);
    }
}
