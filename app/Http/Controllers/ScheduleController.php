<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Course;

class ScheduleController extends Controller
{
    public function index()
    {
        // Получаем расписание текущего учителя
        $schedules = Schedule::where('teacher_id', auth()->id())->orderBy('date')->orderBy('start_time')->get();

        return view('teacher.schedule', compact('schedules'));
    }

    public function create()
    {
        $courses = Course::all(); // Получаем все курсы
        return view('teacher.schedule-create', compact('courses')); // Передаём переменную courses
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'course_id' => 'required|exists:courses,id', // Проверяем, что курс существует
            'location' => 'nullable|string|max:255',
        ]);

        Schedule::create([
            'teacher_id' => auth()->id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'course_id' => $request->course_id,
            'location' => $request->location,
        ]);

        return redirect()->route('teacher.schedule')->with('success', 'Занятие добавлено!');
    }

    public function destroy(Schedule $schedule)
    {
        // Удаляем только если это расписание текущего учителя
        if ($schedule->teacher_id !== auth()->id()) {
            abort(403, 'Нет доступа');
        }

        $schedule->delete();

        return redirect()->route('teacher.schedule')->with('success', 'Занятие удалено!');
    }
}

