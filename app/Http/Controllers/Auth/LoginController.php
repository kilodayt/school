<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request; // Исправленный импорт
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Переопределяем метод для редиректа после авторизации.
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function authenticated(Request $request, $user)
    {
        // Проверяем, есть ли intended URL в сессии или в localStorage
        $intendedUrl = session('url.intended') ?: $request->session()->get('intended_url');

        // Если intended URL найден, перенаправляем пользователя туда
        if ($intendedUrl) {
            return redirect()->to($intendedUrl);
        }

        // Иначе перенаправляем на главную страницу
        return redirect($this->redirectTo);
    }
}
