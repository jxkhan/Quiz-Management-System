<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade'); // Relation with Quiz
            $table->text('question_text');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer']);
            $table->json('options')->nullable(); // Options for multiple-choice questions
            $table->text('answer'); // Correct answer
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
