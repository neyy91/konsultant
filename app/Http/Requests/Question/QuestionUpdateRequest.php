<?php

namespace App\Http\Requests\Question;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\CategoryLaw;
use App\Models\Question;

class QuestionUpdateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/question.php
     * @var array
     */
    protected $translateAttributes = ['from', 'category_law_id', 'title', 'description', 'file', 'city_id', 'status', 'sticky'];
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
        // $froms = implode(',', Question::getFromKeys());
        $statuses = implode(',', Question::getStatusKeys());
        $tableCity = (new City)->getTable();
        $tableCategoryLaw = (new CategoryLaw)->getTable();

        return [
            'status' => "required|in:$statuses",
            'sticky' => "nullable|boolean",
            'category_law_id' => "required|exists:$tableCategoryLaw,id,parent_id,NOT_NULL,status," . CategoryLaw::PUBLISHED,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:' . config('site.question.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.question.file.max_size', 500) . '|mimes:' . config('site.question.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:$tableCity,id,status," . City::PUBLISHED,
        ];
    }

    public function getTransTemplate()
    {
        return 'question.form.:attribute';
    }
}
