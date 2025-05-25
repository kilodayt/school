<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Course;
use App\Models\Progress;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /** Главная страница администрирования */
    public function index(): View
    {
        // Получение всех курсов, пользователей и уроков
        $courses = Course::all();
        $users = User::all();
        $lessons = Lesson::all();

        // 1. Распределение ролей пользователей
        $rolesStats = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role');

        // 2. Участники по курсам
        $coursesStats = DB::table('users_courses')
            ->select('course_id', DB::raw('count(*) as count'))
            ->groupBy('course_id')
            ->pluck('count', 'course_id')
            ->mapWithKeys(function($count, $courseId) {
                $title = Course::find($courseId)->title;
                return [$title => $count];
            });

        // 3. Завершённость уроков по датам
        $completionRaw = Progress::select(
            DB::raw("DATE(updated_at) as date"),
            DB::raw('count(*) as count')
        )
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $completionData = [
            'labels' => $completionRaw->pluck('date'),
            'values' => $completionRaw->pluck('count'),
        ];

        // 4. Новые пользователи за последние 7 дней
        $newUsersRaw = User::where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $newUsersData = [
            'labels' => $newUsersRaw->pluck('date'),
            'values' => $newUsersRaw->pluck('count'),
        ];

        // Передаём всё в шаблон
        return view('admin.dashboard', compact(
            'courses', 'users', 'lessons',
            'rolesStats', 'coursesStats',
            'completionData', 'newUsersData'
        ));
    }
}

