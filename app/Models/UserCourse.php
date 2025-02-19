<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    // Указываем таблицу, если она отличается от имени модели во множественном числе
    protected $table = 'users_courses';

    // Разрешаем массовое присвоение для следующих полей
    protected $fillable = ['user_id', 'course_id'];

    /**
     * Определяем связь с моделью User (пользователь)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Определяем связь с моделью Course (курс)
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
