<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class PythonCompilerController extends Controller
{
    public function execute(Request $request)
    {
        try {
            // Получаем код и идентификатор задания из запроса
            $code = $request->input('code');
            $lesson_id = $request->input('lesson_id');

            // Допустимые результаты для каждого задания (массив возможных ответов)
            $expectedOutputs = [
                1 => ['hello world', 'Hello, world!', 'HELLO WORLD', 'Hello, world', 'Hello world'],
                2 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
                3 => ['Python is awesome', 'I love Python', 'Python is great'],
                21 => [1],
                22 => [1],
                // Добавить все задания
            ];

            // Проверяем, что задание с таким ID существует
            if (!isset($expectedOutputs[$lesson_id])) {
                return response()->json(['error' => 'Неверный идентификатор задания.'], 400);
            }

            $acceptableOutputs = $expectedOutputs[$lesson_id];

            // Сохраняем код во временный файл
            $filePath = storage_path('app/temp_code.py');
            file_put_contents($filePath, $code);

            // Укажите точный путь к вашему интерпретатору Python
            $pythonPath = base_path('Python38/python.exe'); // или base_path('python3/bin/python') для Linux/Mac

            // Проверка синтаксиса перед выполнением
            $syntaxCheckResult = $this->checkSyntax($filePath);
            if ($syntaxCheckResult !== "No syntax errors.") {
                return response()->json(['output' => $syntaxCheckResult], 400);
            }

            // Запускаем процесс выполнения Python
            $process = new Process([$pythonPath, $filePath]);
            $process->run();

            // Проверяем наличие ошибок
            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                $filteredErrorOutput = preg_replace('/File "(.*?)"/', 'File "main.py"', $errorOutput);
                return response()->json(['output' => $filteredErrorOutput], 400);
            }

            // Получаем вывод от выполнения кода
            $output = trim($process->getOutput());

            // Проверка, совпадает ли вывод с любым из допустимых вариантов
            $isCorrect = in_array($output, $acceptableOutputs);

            return response()->json([
                'output' => $output,
                'isCorrect' => $isCorrect,
                'message' => $isCorrect ? 'Задание выполнено правильно!' : 'Неправильный ответ. Попробуйте снова.',
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
}

