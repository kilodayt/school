<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    /** Главная страница блога */
    public function index(): View
    {
        // Получаем список всех постов
        $posts = Post::latest()->paginate(10);

        return view('blog.index', compact('posts'));
    }

    /** Страница статьи */
    public function show($slug): View
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
