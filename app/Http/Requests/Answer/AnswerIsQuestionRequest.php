<?php

namespace App\Http\Requests\Answer;

use App\Http\Requests\Request;

class AnswerIsQuestionRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/answer.php
     * @var array
     */
    protected $translateAttributes = ['comment', 'rate'];

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
            'comment' => 'nullable|string|max:' . config('site.like.text_max', 1000),
            'rate' => 'required|integer|min:1|max:5',
        ];
    }

    public function getTransTemplate()
    {
        return 'answer.form.:attribute';
    }
}
