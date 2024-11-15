<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExistingTables extends Migration
{
    public function up()
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->change();
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
        });

        Schema::table('lesson_details', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->change();
            $table->foreign('lesson_id')->references('lesson_id')->on('lessons')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['lesson_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });

        Schema::table('lesson_details', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['course_id']);
        });

        Schema::table('users_courses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
        });
    }
}
