<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Services\CourseService;
use Illuminate\View\View;

class CourseController extends Controller
{
    protected $courseService;

    /** Инициализация сервиса через конструктор */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /** Главная страница курсов */
    public function index(): View
    {
        $courses = $this->courseService->getAllCourses();
        return view('courses.index', compact('courses'));
    }

    /** Страница выбранного курса */
    public function show($id): View
    {
        // Загружаем курс с его уроками и проверяем наличие у пользователя курсов
        $course = $this->courseService->getCourseById($id);
        $hasCourse = false;

        if (Auth::check()) {
            $hasCourse = $this->courseService->hasCourse($id);
        }

        return view('courses.show', compact('course', 'hasCourse'));
    }

    /** Показать форму для создания нового курса */
    public function create(): View
    {
        return view('admin.courses.create');
    }

    /** Сохранение нового курса */
    public function store(Request $request): RedirectResponse
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


    /** Редактирование курса */
    public function edit(Course $course): View
    {
        return view('admin.courses.edit', compact('course'));
    }

    /** Обновление данных курса */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->courseService->updateCourse($course, $data);

        return redirect()->route('admin.courses')->with('success', 'Курс обновлён!');
    }

    /** Удаление курса */
    public function destroy($id): RedirectResponse
    {
        $this->courseService->deleteCourse($id);

        return redirect()->route('admin.courses')->with('success', 'Курс удалён!');
    }
}

