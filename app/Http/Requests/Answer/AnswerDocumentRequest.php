<?php

namespace App\Http\Requests\Answer;

use App\Http\Requests\Request;

class AnswerDocumentRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/answer.php
     * @var array
     */
    protected $translateAttributes = ['text', 'cost', 'file'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: установка разрешения
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text' => 'required|string|max:' . config('site.answer.text_max', 5000),
            'cost' => 'required|integer|min:' . config('site.document.min_price', 800),
            'file' => 'nullable|file|max:' . config('site.answer.file.max_size', 500) . '|mimes:' . config('site.answer.file.mimes', 'txt,doc,pdf'),
        ];
    }

    public function getTransTemplate()
    {
        return 'answer.form.:attribute';
    }
}
