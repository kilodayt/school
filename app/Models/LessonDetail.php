<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonDetail extends Model
{
    use HasFactory;

    protected $table = 'lessons_details';

    protected $fillable = [
        'lesson_id',
        'course_id',
        'theory_1',
        'theory_2',
        'theory_3',
        'exessize'
    ];

    public $timestamps = false;

    public function lesson()
    {
        return $this->belongsTo(Lesson::class,'lesson_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }
}
