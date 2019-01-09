<?php

namespace App\Http\Requests\Answer;

use App\Http\Requests\Request;
use App\Models\Answer;

class AnswersFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/answer.php
     * @var array
     */
    protected $translateAttributes = ['text', 'is'];

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
            'text' => 'nullable|string|max:255',
            'is' => 'nullable|boolean',
            'order' => 'nullable|array',
            'order.*' => 'string',
        ];
    }

    public function getTransTemplate()
    {
        return 'answer.form.:attribute';
    }

}
