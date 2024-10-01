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
    Schema::table('pending_students', function (Blueprint $table) {
        $table->string('status')->default('pending'); // You can set a default value
    });
}

public function down()
{
    Schema::table('pending_students', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
