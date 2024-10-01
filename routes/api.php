<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\PendingStudentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizAssignmentController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoleController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register'])->name('auth.register');
    Route::post('/login', [UserController::class, 'login'])->name('auth.login');
    Route::post('/password/forgot', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');
});

Route::post('/register-student', [PendingStudentController::class, 'register'])->name('student.register');
Route::get('/admin/pending-students', [PendingStudentController::class, 'getAllPendingStudents']);
Route::post('/admin/accept', [AdminController::class, 'accept'])->name('admin.approve');
Route::post('/admin/reject', [AdminController::class, 'reject'])->name('admin.reject');
Route::get('/roles-with-permissions', [RoleController::class, 'getRolesWithPermissions'])->name('roles.permissions');

// Routes that require authentication
Route::middleware(['auth:api'])->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::post('/register-manager', [AdminController::class, 'registerManager'])->name('admin.register_manager');
        Route::post('/register-supervisor', [AdminController::class, 'registerSupervisor'])->name('admin.register_supervisor');

        // Quiz routes for admin
        Route::prefix('quiz')->group(function () {
            Route::post('/create', [QuizController::class, 'createQuiz'])->name('quiz.create');
            Route::get('/all', [QuizController::class, 'getAllQuizzes'])->name('quiz.all');

            // Question routes
            Route::post('/{quiz_id}/questions', [QuestionController::class, 'store'])->name('question.store');
            Route::get('/{quiz_id}/questions', [QuestionController::class, 'index'])->name('question.index');
            Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('question.delete');
        });
    });

    // Manager & Supervisor routes
    Route::middleware(['role:manager|supervisor'])->prefix('manager')->group(function () {
        Route::post('quiz/assign/{quiz_id}/{student_id}', [QuizAssignmentController::class, 'assignQuizToStudent'])->name('quiz.assign');
        Route::get('quiz/assigned/{student_id}', [QuizAssignmentController::class, 'getAssignedQuizzes'])->name('quiz.assigned');
    });

    // Student routes
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::post('quiz/attempt/{quiz_assignment_id}', [QuizAttemptController::class, 'attemptQuiz'])->name('quiz.attempt');
        Route::post('quiz/submit/{quiz_assignment_id}', [QuizAttemptController::class, 'submitQuiz'])->name('quiz.submit');
        Route::get('quiz/results', [QuizAttemptController::class, 'viewResults'])->name('quiz.results');
    });
});