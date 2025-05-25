<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_checks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                   ->constrained('lessons')
                   ->onDelete('cascade')
                   ->unique();

            // Два JSON-поля (для required и forbidden)
            $table->json('required')->nullable();
            $table->json('forbidden')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_checks');
    }
};
