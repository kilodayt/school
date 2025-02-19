<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RemovePoweredBy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Удаляем заголовок X-Powered-By
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}

