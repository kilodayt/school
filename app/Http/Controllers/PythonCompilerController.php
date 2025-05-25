<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecutePythonRequest;
use App\Services\PythonRunnerService;
use Illuminate\Http\JsonResponse;

class PythonCompilerController extends Controller
{
    public function execute(ExecutePythonRequest $r, PythonRunnerService $runner): JsonResponse
    {
        $data = $r->validate([
            'code'      => 'required|string',
            'course_id' => 'required|integer',
            'lesson_id' => 'required|integer',
            'language'  => 'required|string|in:python,cpp,php',
        ]);

        return $runner
            ->runCode($data['code'], $data['language'], $data['course_id'], $data['lesson_id']);
    }
}
