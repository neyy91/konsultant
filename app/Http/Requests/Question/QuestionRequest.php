<?php

namespace App\Http\Requests\Question;

use Auth;
use Gate;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\CategoryLaw;
use App\Models\Question;
use App\Models\User;


class QuestionRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/question.php
     * @var array
     */
    protected $translateAttributes = ['category_law_id', 'title', 'description', 'file', 'city_id', 'firstname', 'email', 'telephone'];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest() || Gate::allows('create', Question::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $froms = implode(',', Question::getFromKeys());
        $pays = implode(',', Question::getPayKeys());
        $tableCity = (new City)->getTable();
        $tableCategoryLaw = (new CategoryLaw)->getTable();
        $tableUser = (new User)->getTable();

        return [
            'from' => "required|in:{$froms}",
            'category_law_id' => "required|exists:$tableCategoryLaw,id,status," . CategoryLaw::PUBLISHED,
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:' . config('site.question.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.question.file.max_size', 500) . '|mimes:' . config('site.question.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:$tableCity,id,status," . City::PUBLISHED,
            'telephone' => 'nullable|integer',
            'pay' => "required|in:{$pays}",
        ] + (Auth::guest() ? [
            'firstname' => 'required|string|max:100',
            'email' => "required|email|unique:{$tableUser},email",
        ] : []);
    }

    public function getTransTemplate()
    {
        return 'question.form.:attribute';
    }
}
