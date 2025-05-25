<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    // Поля, которые можно заполнять массово
    protected $fillable = ['user_id','course_id', 'lesson_id', 'status'];

    // Отношение к пользователю
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Отношение к уроку
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
