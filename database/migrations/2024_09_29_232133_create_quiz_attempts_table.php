<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_assignment_id')->constrained('quiz_assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('submitted_at')->nullable();
            $table->integer('result')->nullable();
            $table->string('recording_path')->nullable(); // For storing video recording
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempts');
    }
}
