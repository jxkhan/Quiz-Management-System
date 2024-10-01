<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempt;
use App\Models\QuizAssignment;
use App\Models\QuizAttemptAnswer;
use App\Models\Question;

class QuizAttemptController extends Controller
{
    public function attemptQuiz($quiz_assignment_id)
{
    $assignment = QuizAssignment::findOrFail($quiz_assignment_id);

    // Check if the quiz has already been attempted by the student
    $existingAttempt = QuizAttempt::where('quiz_assignment_id', $quiz_assignment_id)
        ->where('student_id', auth()->user()->id)
        ->first();

    if ($existingAttempt) {
        return response()->json(['message' => 'You have already attempted this quiz.'], 400);
    }

    if ($assignment->status !== 'pending') {
        return response()->json(['message' => 'Quiz not available for attempt!'], 400);
    }

    // Record the start time
    $assignment->status = 'in-progress';
    $assignment->save();

    return response()->json(['message' => 'Quiz started', 'start_time' => now()]);
}

    public function submitQuiz(Request $request, $quiz_assignment_id)
{
    // Validate request
    $request->validate([
        'answers' => 'required|array',
        'recording_path' => 'required|string',
    ]);

    // Find the quiz assignment
    $assignment = QuizAssignment::findOrFail($quiz_assignment_id);

    // Fetch all questions related to the quiz assignment
    $questions = Question::where('quiz_id', $assignment->quiz_id)->get();

    // Initialize score
    $correctAnswersCount = 0;

    // Create a new quiz attempt
    $attempt = QuizAttempt::create([
        'quiz_assignment_id' => $quiz_assignment_id,
        'student_id' => auth()->user()->id,
        'submitted_at' => now(),
        'result' => 0, // Temporarily set result to 0
        'recording_path' => $request->recording_path,
    ]);

    // Loop through each question and compare answers
    foreach ($questions as $question) {
        $submittedAnswer = $request->answers[$question->id] ?? null;

        // Store the submitted answer in the quiz_attempt_answers table
        QuizAttemptAnswer::create([
            'quiz_attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'submitted_answer' => $submittedAnswer,
        ]);

        // Compare submitted answer with the correct answer
        if ($submittedAnswer === $question->answer) {
            $correctAnswersCount++;
        }
    }

    // Calculate the result percentage
    $result = ($correctAnswersCount / count($questions)) * 100;

    // Update the attempt with the final result
    $attempt->result = $result; // Store the calculated result
    $attempt->save();

    // Update assignment status
    $assignment->status = 'completed';
    $assignment->save();

    return response()->json(['message' => 'Quiz submitted successfully!', 'result' => $result]);
}



    public function viewResults($student_id)
{
    $attempts = QuizAttempt::with(['quizAssignment.quiz', 'quizAttemptAnswers.question'])
        ->where('student_id', $student_id)
        ->get();

    return response()->json($attempts);
}
    public function uploadVideo(Request $request)
    {
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $path = $video->store('videos', 'public'); // Store in 'public/videos' directory

            // You can also link the video to the quiz attempt in the database.
            // Assuming you have a `quiz_attempts` table with a `video_url` column:
            $quizAttemptId = $request->quiz_attempt_id;  // Passed from the front-end
            $quizAttempt = QuizAttempt::find($quizAttemptId);
            $quizAttempt->video_url = $path;
            $quizAttempt->save();

            return response()->json(['message' => 'Video uploaded successfully', 'path' => $path], 200);
        }

        return response()->json(['error' => 'No video file provided'], 400);
    }

}
