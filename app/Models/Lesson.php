<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'course_id',
        'lesson_id',
        'title',
        'content',
    ];

    public function course()
    {
        return $this->hasOne(Course::class);
    }
}