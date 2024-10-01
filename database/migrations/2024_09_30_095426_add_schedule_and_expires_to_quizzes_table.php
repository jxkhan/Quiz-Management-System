<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->timestamp('schedule_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['schedule_at', 'expires_at']);
        });
    }
    
};
