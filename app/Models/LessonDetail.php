<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonDetail extends Model
{
    use HasFactory;

    protected $table = 'Lessons_Details';
    protected $fillable = ['lesson_id', 'course_id', 'theory_1', 'theory_2', 'theory_3', 'exessize'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
