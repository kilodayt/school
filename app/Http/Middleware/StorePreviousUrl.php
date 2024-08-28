<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StorePreviousUrl
{
    public function handle($request, Closure $next)
    {
        // Если запрос не относится к логину и не является AJAX-запросом
        if (!$request->expectsJson() && !$request->is('login') && !$request->is('logout')) {
            // Сохраняем текущий URL в сессии
            session(['url.intended' => URL::full()]);
        }

        return $next($request);
    }
}
