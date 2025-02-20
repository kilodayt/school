<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /** Главная страница администрирования */
    public function index(): View
    {
        // Получение всех курсов, пользователей и уроков
        $courses = Course::all();
        $users = User::all();
        $lessons = Lesson::all();

        return view('admin.dashboard', compact('courses', 'users', 'lessons'));
    }
}

