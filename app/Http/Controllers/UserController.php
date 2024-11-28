<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Показать курсы пользователя.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showCourses($id)
    {
        // Находим пользователя по его ID
        $user = User::findOrFail($id);

        // Получаем курсы пользователя
        $courses = $user->courses;

        // Возвращаем представление с данными
        return view('user.courses', ['user' => $user, 'courses' => $courses]);
    }

    public function showForm()
    {
        // Выводим форму
        $users = User::all();
        $courses = DB::table('courses')->get(); // Предполагается, что есть таблица `courses`
        return view('users.add', compact('users', 'courses'));
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'role' => 'required|in:user,teacher,admin',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'remember_token' => Str::random(10),
            ]);

            return response()->json(['success' => 'Пользователь успешно добавлен!', 'user' => $user]);
        } catch (\Exception $e) {
            // Возвращаем сообщение об ошибке для отладки
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignCourse(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Добавляем связь между пользователем и курсом
        DB::table('users_courses')->insert([
            'user_id' => $request->user_id,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('users.add')->with('success', 'Курс успешно назначен пользователю!');
    }
}
