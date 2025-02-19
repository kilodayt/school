<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


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

            return redirect()->route('admin.dashboard')->with('success', 'Пользователь добавлен');
        } catch (\Exception $e) {
            // Возвращаем сообщение об ошибке для отладки
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function assignCourse(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'course_id' => 'required|exists:courses,id',
            ]);

            // Проверяем, есть ли уже этот курс у пользователя
            $exists = DB::table('users_courses')
                ->where('user_id', $request->user_id)
                ->where('course_id', $request->course_id)
                ->exists();

            if ($exists) {
                return redirect()->route('admin.dashboard')->with('error', 'Этот курс уже назначен пользователю.');
            }

            // Добавляем связь между пользователем и курсом
            DB::table('users_courses')->insert([
                'user_id' => $request->user_id,
                'course_id' => $request->course_id,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Курс успешно назначен пользователю!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка: ' . $e->getMessage());
        }
    }


    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,teacher,admin'
        ]);

        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Вы не можете поменять себе роль.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', 'Роль пользователя изменена.');
    }


    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Вы не можете удалить себя.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Пользователь удалён.');
    }

}
