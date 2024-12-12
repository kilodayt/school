<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserCourse;  // Модель для связи пользователей и курсов
use Illuminate\Support\Facades\Auth;  // Для работы с авторизацией

class CourseController extends Controller
{
    public function index()
    {
        // Извлечение всех курсов из базы данных
        $courses = Course::all()->groupBy('language');

        // Возврат представления с данными всех курсов
        return view('courses.index', compact('courses'));
    }

    public function show($id)
    {
        // Найти курс по ID с его уроками
        $course = Course::with('lessons')->findOrFail($id);

        // Проверка, связан ли пользователь с курсом
        $hasCourse = false;

        if (Auth::check()) { // Если пользователь авторизован
            $userId = Auth::id(); // Получаем ID авторизованного пользователя
            $hasCourse = UserCourse::where('user_id', $userId)
                ->where('course_id', $id)
                ->exists();
        }

        // Вернуть представление с данными курса и результатом проверки
        return view('courses.show', compact('course', 'hasCourse'));
    }
}
