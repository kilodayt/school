<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Http\Request;


class PythonController extends Controller
{
    public function runPython(Request $request)
    {
        try {
            $code = $request->input('code');
            $process = new Process(['node', base_path('node_scripts/run_python.js')]);
            $process->setInput($code);
            $process->run();

            if (!$process->isSuccessful()) {
                return response()->json([
                    'success' => false,
                    'error' => $process->getErrorOutput()
                ]);
            }

            return response()->json([
                'success' => true,
                'output' => $process->getOutput()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
