<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecutePythonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code'      => 'required|string|max:1000',
            'lesson_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'code.max' => 'Код слишком длинный. Максимум 1000 символов.',
        ];
    }
}
