<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAttemptAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('submitted_answer'); // This line defines the column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
}


