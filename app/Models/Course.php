<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'description',
        'image',
        'content',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_courses', 'course_id', 'user_id');
    }
}
