<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // Ссылка на таблицу пользователей
            $table->date('date'); // Дата занятия
            $table->time('start_time'); // Время начала
            $table->time('end_time'); // Время окончания
            $table->unsignedInteger('course_id'); // Ссылка на таблицу курсов
            $table->string('location')->nullable(); // Место проведения
            $table->timestamps();

            // Внешние ключи
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
