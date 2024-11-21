<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Таблица, связанная с моделью.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * Массово заполняемые поля модели.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
    ];

    /**
     * Атрибуты, которые должны быть приведены к типу native.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Получить сокращенный контент поста.
     *
     * @param int $limit
     * @return string
     */
    public function excerpt($limit = 150)
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->content), $limit, '...');
    }

    /**
     * Получить URL изображения поста.
     *
     * @return string|null
     */
    public function imageUrl()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
