<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade'); // Relation with Quiz
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade'); // Relation with User (Student)
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade'); // Manager/Supervisor
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_assignments');
    }
}
