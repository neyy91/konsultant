<?php

namespace App\Http\Requests\Question;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;

class QuestionsAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/question.php
     * @var array
     */
    protected $translateAttributes = ['id', 'title', 'category_law_id', 'city_id', 'status', 'sticky'];

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
            'id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'category_law_id' => "nullable|integer",
            'city_id' => "nullable|integer",
            'status' => 'nullable|integer',
            'sticky' => 'nullable|boolean',
            'order' => 'nullable|array',
            'order.*' => 'string',
        ];
    }

    public function getTransTemplate()
    {
        return 'question.form.:attribute';
    }

}
