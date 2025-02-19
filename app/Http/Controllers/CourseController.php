<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // Группируем курсы по языку
        $courses = Course::all()->groupBy('language');
        return view('courses.index', compact('courses'));
    }

    public function show($id)
    {
        // Загружаем курс с его уроками
        $course = Course::with('lessons')->findOrFail($id);
        $hasCourse = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $hasCourse = UserCourse::where('user_id', $userId)
                ->where('course_id', $id)
                ->exists();
        }

        return view('courses.show', compact('course', 'hasCourse'));
    }

    /** 🔹 Метод для создания курса */
    public function create()
    {
        return view('admin.courses.create');
    }

    /** 🔹 Метод для сохранения нового курса */
    public function store(Request $request)
    {
        $request->validate([
            'language' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Сохранение изображения, если загружено
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        // Создание курса
        Course::create([
            'language' => $request->language,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.courses')->with('success', 'Курс создан!');
    }


    /** 🔹 Метод для редактирования курса */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /** 🔹 Метод для обновления курса */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $course->update($request->only('title', 'description'));

        return redirect()->route('admin.courses')->with('success', 'Курс обновлён!');
    }

    /** 🔹 Метод для удаления курса */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Курс удалён!');
    }
}

