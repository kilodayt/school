<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class PythonCompilerController extends Controller
{
    public function execute(Request $request)
    {
        try {
            // Получаем код и идентификатор задания
            $code = $request->input('code');
            $lesson_id = $request->input('lesson_id');

            if (strlen($code) > 1000) {
                return response()->json(['error' => 'Код слишком длинный. Максимум 1000 символов.'], 400);
            }

            if ($this->hasDangerousFunctions($code)) {
                return response()->json(['error' => 'Использование опасных функций запрещено.'], 400);
            }

            // Проверяем синтаксис
            $filePath = storage_path('app/temp_code.py');
            file_put_contents($filePath, $code);
            $syntaxCheckResult = $this->checkSyntax($filePath);
            if ($syntaxCheckResult !== "No syntax errors.") {
                return response()->json(['output' => $syntaxCheckResult], 400);
            }

            // Условия для проверки кода
            $codeChecks = [
                1 => ['required' => ['print'], 'forbidden' => []], // Должен быть print
                2 => ['required' => ['for', 'range'], 'forbidden' => ['while']], // Должен быть цикл for с range
                3 => ['required' => ['def', 'return'], 'forbidden' => []], // Должна быть функция с return
                4 => ['required' => ['and', 'or'], 'forbidden' => []],
                // Добавить условия для других заданий
            ];

            // Проверяем, что задание с таким ID существует
            if (!isset($codeChecks[$lesson_id])) {
                return response()->json(['error' => 'Неверный идентификатор задания.'], 400);
            }

            // Проверяем содержимое кода
            $checkResult = $this->checkCodeContent($code, $codeChecks[$lesson_id]);
            if (!$checkResult['isCorrect']) {
                return response()->json([
                    'output' => implode("", $checkResult['errors']),
                    'isCorrect' => false,
                    'message' => 'Код не соответствует условиям задания. Проверьте ошибки.',
                ], 400);
            }

            // Выполняем код
            $pythonPath = base_path('Python38/python.exe');
            $process = new Process([$pythonPath, $filePath]);
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = preg_replace('/File "(.*?)"/', 'File "main.py"', $process->getErrorOutput());
                return response()->json(['output' => $errorOutput], 400);
            }

            $output = trim($process->getOutput());
            return response()->json([
                'output' => $output,
                'isCorrect' => true,
                'message' => 'Задание выполнено правильно!',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function checkSyntax($filePath)
    {
        $command = escapeshellcmd("python -m py_compile $filePath");
        $output = shell_exec($command);

        if (strpos($output, 'Error') !== false) {
            return "Syntax error found!";
        }

        return "No syntax errors.";
    }

    private function checkCodeContent($code, $conditions)
    {
        $errors = [];
        $isCorrect = true;

        // Проверка обязательных ключевых слов
        foreach ($conditions['required'] as $keyword) {
            if (strpos($code, $keyword) === false) {
                $errors[] = "Ключевое слово '{$keyword}' не найдено.\n";
                $isCorrect = false;
            }
        }

        // Проверка запрещённых ключевых слов
        foreach ($conditions['forbidden'] as $keyword) {
            if (strpos($code, $keyword) !== false) {
                $errors[] = "Ключевое слово '{$keyword}' запрещено.\n";
                $isCorrect = false;
            }
        }

        return ['isCorrect' => $isCorrect, 'errors' => $errors];
    }

    private function hasDangerousFunctions($code)
    {
        $dangerousKeywords = [
            'os.system', 'subprocess', 'eval', 'exec', 'open', '__import__',
            'webbrowser', 'compile', 'globals', 'locals', 'delattr', 'setattr',
            'getattr', 'vars', 'dir', 'os.popen', 'shutil', 'sys.modules',
            'socket', 'ctypes', 'multiprocessing', 'threading', 'pickle',
            'marshal', 'base64', 'tempfile', 'jsonpickle',
        ];
        $dangerousModules = ['os', 'sys', 'subprocess', 'shutil', 'socket', 'ctypes', 'pickle', 'multiprocessing'];

        foreach ($dangerousKeywords as $keyword) {
            if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $code)) {
                return true;
            }
        }

        foreach ($dangerousModules as $module) {
            if (preg_match('/\bimport\s+' . preg_quote($module, '/') . '\b/', $code) ||
                preg_match('/\bfrom\s+' . preg_quote($module, '/') . '\s+import\b/', $code)) {
                return true;
            }
        }

        if (preg_match('/[\'"]\/.*?[\'"]/', $code)) {
            return true;
        }

        if (preg_match('/e.*v.*a.*l/', $code)) {
            return true;
        }

        return false;
    }
}

