<?php

namespace App\Http\Requests;

use Auth;

use App\Http\Requests\Request;
use App\Models\Expertise;
use App\Models\Question;
use App\Models\Lawyer;
use App\Models\User;

class ExpertisesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/expertise.php
     * @var array
     */
    protected $translateAttributes = ['qid', 'lid', 'type'];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('admin', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tableQuestion = (new Question)->getTable();
        $tableLawyer = (new Lawyer)->getTable();

        return [
            'qid' => "nullable|exists:{$tableQuestion},id",
            'lid' => "nullable|exists:{$tableLawyer},id",
            'types' => 'nullable|in:' . implode(',', array_keys(Expertise::getTypes())),
        ];
    }

    public function getTransTemplate()
    {
        return 'expertise.form.:attribute';
    }

    
}
