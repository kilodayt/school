<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LessonService;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Lesson;

class LessonController extends Controller
{
    protected $lessonService;

    // Инъекция сервиса через конструктор
    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    // Пример метода index с использованием сервиса
    public function index($course_id)
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

    // Пример show
    public function show($course_id, $lesson_id)
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

    // Показать форму для создания нового урока
    public function create($course_id)
    {
        $course = Course::findOrFail($course_id); // Получаем курс по ID
        return view('admin.lessons.create', compact('course')); // Передаём курс в представление
    }

    // Пример store
    public function store(Request $request, $course_id)
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

    public function edit($course_id, $lesson_id)
    {
        $lesson = $this->lessonService->editForm($course_id, $lesson_id);

        return view('admin.lessons.edit', compact('lesson'));
    }

    // Пример update
    public function update(Request $request, $course_id, $lesson_id)
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

    // Пример destroy
    public function destroy($id)
    {
        $this->lessonService->deleteLesson($id);

        return redirect()
            ->route('admin.lessons.index')
            ->with('success', 'Урок удален успешно!');
    }

    // Пример editLessonDetails
    public function editLessonDetails($course_id, $lesson_id)
    {
        $lessonDetails = $this->lessonService->getLessonDetails($course_id, $lesson_id);

        // Можно дополнительно получить сам урок или другую информацию
        $lesson = $this->lessonService
            ->showLesson($course_id, $lesson_id, Auth::id())['lesson'] ?? null;

        return view('admin.lessons.edit-details', compact('lesson', 'lessonDetails'));
    }

    // Пример updateLessonDetails
    public function updateLessonDetails(Request $request, $course_id, $lesson_id)
    {
        $request->validate([
            'theory_1' => 'nullable|string',
            'theory_2' => 'nullable|string',
            'theory_3' => 'nullable|string',
            'exessize' => 'nullable|string',
        ]);

        $this->lessonService->updateLessonDetails($course_id, $lesson_id, $request->all());

        return redirect()
            ->route('admin.lessons.edit', ['course_id' => $course_id, 'lesson_id' => $lesson_id])
            ->with('success', 'Теоретический материал обновлён!');
    }
}
