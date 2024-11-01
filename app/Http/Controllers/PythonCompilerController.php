<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonCompilerController extends Controller
{
    public function execute(Request $request)
    {
        try {
            // Получаем код из запроса
            $code = $request->input('code');

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
                // Получаем оригинальный вывод ошибки
                $errorOutput = $process->getErrorOutput();

                // Убираем путь к файлу с помощью регулярного выражения
                $filteredErrorOutput = preg_replace('/File "(.*?)"/', 'File "main.py"', $errorOutput);

                // Возвращаем отфильтрованное сообщение об ошибке
                return response()->json(['output' => $filteredErrorOutput], 400);
            }

            // Возвращаем результат выполнения
            return response()->json(['output' => $process->getOutput()]);
        } catch (\Exception $e) {
            // Ловим любые ошибки и возвращаем их
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function checkSyntax($filePath)
    {
        // Проверка синтаксиса с помощью компиляции
        $command = escapeshellcmd("python -m py_compile $filePath");
        $output = shell_exec($command);

        if (strpos($output, 'Error') !== false) {
            // Возвращаем ошибку
            return "Syntax error found!";
        }

        return "No syntax errors.";
    }

    private function formatPythonCode($code)
    {
        // Правило для добавления отступов перед строками, которые начинаются с ключевых слов блоков кода
        $code = preg_replace('/\b(def|class|if|else|for|while|try|except|with)\b/', '    $0', $code);

        // Замена табуляции на 4 пробела для соответствия стилю Python
        $code = str_replace("\t", "    ", $code);

        return $code;
    }

    // Удален метод runPythonCode, так как его логика была интегрирована в execute
}
