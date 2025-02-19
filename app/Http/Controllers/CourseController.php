<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Services\CourseService;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return view('courses.index', compact('courses'));
    }

    public function show($id)
    {
        // Загружаем курс с его уроками
        $course = $this->courseService->getCourseById($id);
        $hasCourse = false;

        if (Auth::check()) {
            $hasCourse = $this->courseService->hasCourse($id);
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
        // Валидация входных данных
        $data = $request->validate([
            'language' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Создание курса через сервис
        $this->courseService->createCourse($data);

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
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->courseService->updateCourse($course, $data);

        return redirect()->route('admin.courses')->with('success', 'Курс обновлён!');
    }

    /** 🔹 Метод для удаления курса */
    public function destroy($id)
    {
        $this->courseService->deleteCourse($id);

        return redirect()->route('admin.courses')->with('success', 'Курс удалён!');
    }
}

