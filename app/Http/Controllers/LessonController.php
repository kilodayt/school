<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\LessonDetail;
use App\Models\Course;

class LessonController extends Controller
{
    // Отображение списка уроков
    public function index($course_id)
    {
        $course = Course::with('lessons')->findOrFail($course_id); // Найти курс по ID вместе с уроками
        $lessons = $course->lessons; // Получить все уроки курса

        return view('lessons.index', compact('course', 'lessons')); // Передаем курс и уроки в представление
    }

    // Показать форму для создания нового урока
    public function create()
    {
        return view('lessons.create'); // Отображаем форму для создания нового урока
    }

    // Сохранение нового урока
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Lesson::create($request->all()); // Сохраняем новый урок в базе данных

        return redirect()->route('lessons.index')->with('success', 'Урок создан успешно!');
    }

    // Показать конкретный урок
    public function show($course_id, $lesson_id)
    {
        // Проверка диапазона lesson_id
        if ($lesson_id < 1 || $lesson_id > 20) {
            abort(404, 'Урок не найден'); // Возвращаем 404, если lesson_id вне диапазона
        }

        // Получаем курс с уроками
        $course = Course::with('lessons')->findOrFail($course_id);

        // Получаем конкретный урок по course_id и lesson_id
        $lesson = Lesson::where('course_id', $course_id)
            ->where('lesson_id', $lesson_id)
            ->first();

        // Если урок не найден
        if (!$lesson) {
            abort(404, 'Урок не найден');
        }

        // Получаем детали урока
        $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->first();

        // Получаем все уроки курса
        $lessons = $course->lessons;

        // Передаем данные в представление
        return view('lessons.show', compact('course', 'lesson', 'lessonDetails', 'lessons'));
    }

    // Показать форму для редактирования урока
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id); // Извлекаем урок по ID
        return view('lessons.edit', compact('lesson')); // Отображаем форму редактирования
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

        return redirect()->route('lessons.index')->with('success', 'Урок обновлен успешно!');
    }

    // Удаление урока
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id); // Извлекаем урок по ID
        $lesson->delete(); // Удаляем урок

        return redirect()->route('lessons.index')->with('success', 'Урок удален успешно!');
    }
}
