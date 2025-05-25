<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonCheck extends Model
{
    protected $table = 'lesson_checks';

    // Разрешаем массовое заполнение нужных полей (если используем fill() или create())
    protected $fillable = [
        'course_id',
        'lesson_id',
        'required',
        'forbidden',
    ];

    // Преобразуем JSON-данные в массив автоматически
    protected $casts = [
        'required'  => 'array',
        'forbidden' => 'array',
    ];
}
