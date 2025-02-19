<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [

        'course_id',
        'lesson_id',
        'title',
        'content',
    ];
    public $timestamps = false;

    public function course()
    {
        return $this->hasOne(Course::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class, 'lesson_id');
    }

    public function details()
    {
        return $this->hasMany(LessonDetail::class, 'lesson_id');
    }

}
