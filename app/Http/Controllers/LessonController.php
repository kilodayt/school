<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\LessonDetail;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    // Отображение списка уроков
    public function index($course_id)
    {
        $course = Course::with('lessons')->findOrFail($course_id); // Найти курс по ID вместе с уроками
        $lessons = $course->lessons; // Получить все уроки курса

        $userId = Auth::id();
        $completedLessons = Progress::where('user_id', $userId)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();
        $completedLessonsCount = count($completedLessons);

        return view('lessons.index', compact(
            'course',
            'lessons',
            'completedLessons',
            'totalLessons',
            'completedLessonsCount')); // Передаем курс и уроки в представление
    }

    // Показать форму для создания нового урока
    public function create($course_id)
    {
        $course = Course::findOrFail($course_id); // Получаем курс по ID
        return view('admin.lessons.create', compact('course')); // Передаём курс в представление
    }


    // Сохранение нового урока
    public function store(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);

        // Проверяем, что уроков меньше 20
        if ($course->lessons->count() >= 20) {
            return redirect()->route('admin.courses.edit', $course_id)
                ->with('error', 'Нельзя создать больше 20 уроков.');
        }

        $request->validate([
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'theory_1' => 'required|string',
            'theory_2' => 'required|string',
            'theory_3' => 'required|string',
            'exessize' => 'required|string',
        ]);

        // Создаём урок
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'lesson_id' => $request->lesson_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // Создаём LessonDetails
        LessonDetail::create([
            'lesson_id' => $lesson->lesson_id,
            'course_id' => $course->id,
            'theory_1' => $request->theory_1,
            'theory_2' => $request->theory_2,
            'theory_3' => $request->theory_3,
            'exessize' => $request->exessize,
        ]);

        return redirect()->route('admin.courses.edit', $course_id)->with('success', 'Урок создан успешно!');
    }

    // Показать конкретный урок
    public function show($course_id, $lesson_id)
    {
        if ($lesson_id < 1 || $lesson_id > 20) {
            abort(404, 'Урок не найден');
        }

        $course = Course::with('lessons')->findOrFail($course_id);

        $lesson = Lesson::where('course_id', $course_id)
            ->where('lesson_id', $lesson_id)
            ->first();

        if (!$lesson) {
            abort(404, 'Урок не найден');
        }

        $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)
            ->where('course_id', $course_id)
            ->first();

        function formatText($text) {
            return nl2br(e($text));
        }

        $theory1Text = formatText($lessonDetails->theory_1);
        $theory2Text = formatText($lessonDetails->theory_2);
        $theory3Text = formatText($lessonDetails->theory_3);
        $exessizeText = formatText($lessonDetails->exessize);
        $lessons = $course->lessons;

        // Получение прогресса пользователя
        $userId = Auth::id();
        $completedLessons = Progress::where('user_id', $userId)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();
        $completedLessonsCount = count($completedLessons);

        // Передача данных в представление
        return view('lessons.show', compact(
            'course',
            'lesson',
            'lessonDetails',
            'lessons',
            'theory1Text',
            'theory2Text',
            'theory3Text',
            'exessizeText',
            'completedLessons',
            'totalLessons',
            'completedLessonsCount'
        ));
    }

    // Показать форму для редактирования урока
    public function edit($course_id, $lesson_id)
    {
        $lesson = Lesson::where('course_id', $course_id)
            ->where('lesson_id', $lesson_id)
            ->firstOrFail();

        return view('admin.lessons.edit', compact('lesson'));
    }


    // Обновление урока
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $lesson = Lesson::findOrFail($id); // Извлекаем урок по ID
        $lesson->update($request->all()); // Обновляем урок

        return redirect()->route('admin.lessons.index')->with('success', 'Урок обновлен успешно!');
    }

    // Удаление урока
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id); // Извлекаем урок по ID
        $lesson->delete(); // Удаляем урок

        return redirect()->route('admin.lessons.index')->with('success', 'Урок удален успешно!');
    }

    public function editLessonDetails($course_id, $lesson_id)
    {
        $lesson = Lesson::where('course_id', $course_id)->where('lesson_id', $lesson_id)->firstOrFail();
        $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->where('course_id', $course_id)->firstOrCreate([
            'lesson_id' => $lesson_id,
            'course_id' => $course_id
        ]);

        return view('admin.lessons.edit-details', compact('lesson', 'lessonDetails'));
    }

    public function updateLessonDetails(Request $request, $course_id, $lesson_id)
    {
        $request->validate([
            'theory_1' => 'nullable|string',
            'theory_2' => 'nullable|string',
            'theory_3' => 'nullable|string',
            'exessize' => 'nullable|string',
        ]);

        $lessonDetails = LessonDetail::updateOrCreate(
            ['lesson_id' => $lesson_id, 'course_id' => $course_id],
            $request->only(['theory_1', 'theory_2', 'theory_3', 'exessize'])
        );

        return redirect()->route('admin.lessons.edit', ['course_id' => $course_id, 'lesson_id' => $lesson_id])
            ->with('success', 'Теоретический материал обновлён!');
    }


}
