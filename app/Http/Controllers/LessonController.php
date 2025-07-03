<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Services\LessonService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\View\View;
use App\Models\LessonCheck;


class LessonController extends Controller
{
    protected $lessonService;

    /** Инициализация сервиса через конструктор */
    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /** Главная страница уроков */
    public function index($course_id): View
    {
        $userId = Auth::id();
        $data = $this->lessonService->getLessonsWithCompletion($course_id, $userId);

        return view('lessons.index', [
            'course'                => $data['course'],
            'lessons'               => $data['lessons'],
            'completedLessons'      => $data['completedLessons'],
            'totalLessons'          => $data['totalLessons'],
            'completedLessonsCount' => $data['completedLessonsCount'],
        ]);
    }

    /** Страница выбранного урока */
    public function show($course_id, $lesson_id): View
    {
        $userId = Auth::id();
        $data = $this->lessonService->showLesson($course_id, $lesson_id, $userId);

        return view('lessons.show', [
            'course'                => $data['course'],
            'lesson'                => $data['lesson'],
            'lessonDetails'         => $data['lessonDetails'],
            'lessons'               => $data['lessons'],
            'theory1Text'           => $data['theory1Text'],
            'theory2Text'           => $data['theory2Text'],
            'theory3Text'           => $data['theory3Text'],
            'exessizeText'          => $data['exessizeText'],
            'completedLessons'      => $data['completedLessons'],
            'totalLessons'          => $data['totalLessons'],
            'completedLessonsCount' => $data['completedLessonsCount'],
        ]);
    }

    /** Показать форму для создания нового урока */
    public function create($course_id): View
    {
        $course = Course::findOrFail($course_id); // Получаем курс по ID
        return view('admin.lessons.create', compact('course')); // Передаём курс в представление
    }

    /** Сохранение нового урока */
    public function store(Request $request, $course_id): RedirectResponse
    {
        $request->validate([
            'lesson_id' => 'required|integer',
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'theory_1'  => 'required|string',
            'theory_2'  => 'required|string',
            'theory_3'  => 'required|string',
            'exessize'  => 'required|string',
        ]);

        $result = $this->lessonService->createLesson($course_id, $request->all());

        if ($result['error']) {
            return redirect()
                ->route('admin.courses.edit', $course_id)
                ->with('error', $result['message']);
        }

        return redirect()
            ->route('admin.courses.edit', $course_id)
            ->with('success', $result['message']);
    }

    /** Показать форму для редактирования урока */
    public function edit($course_id, $lesson_id): View
    {
        $lesson = $this->lessonService->editForm($course_id, $lesson_id);

        return view('admin.lessons.edit', compact('lesson'));
    }

    /** Обновление данных урока */
    public function update(Request $request, $course_id, $lesson_id): RedirectResponse
    {
        $request->validate([
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
        ]);

        $this->lessonService->updateLesson($request->all(), $course_id, $lesson_id);

        return redirect()
            ->route('admin.lessons.edit', ['course_id' => $course_id, 'lesson_id' => $lesson_id])
            ->with('success', 'Урок обновлен успешно!');
    }

    /** Показать форму для редактирования деталей урока */
    public function editLessonDetails($course_id, $lesson_id): View
    {
        // детали урока
        $lessonDetails = $this->lessonService->getLessonDetails($course_id, $lesson_id);

        // сам урок
        $lesson = $this->lessonService
            ->showLesson($course_id, $lesson_id, Auth::id())['lesson'];

        // проверки из lesson_checks
        $lessonCheck = LessonCheck::firstOrNew([
            'course_id' => $course_id,
            'lesson_id' => $lesson_id,
        ]);

        return view('admin.lessons.edit-details', compact(
            'lesson', 'lessonDetails', 'lessonCheck'
        ));
    }

    /** Обновление данных деталей урока */
    public function updateLessonDetails(Request $request, $course_id, $lesson_id): RedirectResponse
    {
        // валидация для теории
        $request->validate([
            'theory_1'  => 'nullable|string',
            'theory_2'  => 'nullable|string',
            'theory_3'  => 'nullable|string',
            'exessize'  => 'nullable|string',
            'required'  => 'nullable|string',
            'forbidden' => 'nullable|string',
        ]);

        // сначала обновляем теорию
        $this->lessonService->updateLessonDetails($course_id, $lesson_id, $request->all());

        // теперь парсим и сохраняем проверки
        $required  = array_filter(
            array_map('trim', explode(',', $request->input('required', '')))
        );
        $forbidden = array_filter(
            array_map('trim', explode(',', $request->input('forbidden', '')))
        );

        LessonCheck::updateOrCreate(
            ['course_id' => $course_id, 'lesson_id' => $lesson_id],
            ['required' => $required, 'forbidden' => $forbidden]
        );

        return redirect()
            ->route('admin.lessons.details.edit', [
                'course_id' => $course_id,
                'lesson_id' => $lesson_id
            ])
            ->with('success', 'Данные урока и проверки сохранены!');
    }

    /** Удаление урока */
    public function destroy($id): RedirectResponse
    {
        $this->lessonService->deleteLesson($id);

        return redirect()
            ->route('admin.lessons.index')
            ->with('success', 'Урок удален успешно!');
    }
}
