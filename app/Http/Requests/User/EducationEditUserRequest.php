<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\Education;

class EducationEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['country', 'city', 'university', 'faculty', 'year'];

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
        $years = Education::getYears();
        return [
            'country' => 'required|string|max:30',
            'city' => 'required|string|max:50',
            'university' => 'required|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'year' => 'required|integer|min:' .$years[0] . '|max:' . end($years),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.education.:attribute';
    }

}
