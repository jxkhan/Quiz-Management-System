<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('created_by')->constrained('users'); // Admin ID (relation with User)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
