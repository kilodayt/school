<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        // Извлечение всех курсов из базы данных
        $courses = Course::all();

        // Возврат представления с данными всех курсов
        return view('courses.index', compact('courses'));
    }

    public function show($id)
    {
        // Найти курс по ID
        $course = Course::with('lessons')->findOrFail($id);

        // Вернуть представление с данными курса
        return view('courses.show', compact('course'));
    }
}
