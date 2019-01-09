<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;


class ExperienceEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['specialization', 'company', 'position', 'experience'];

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
            'specialization' => 'nullable|array|max:5',
            'specialization.*' => 'integer',
            'company' => 'nullable|string|max:255',
            'weekdays' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|between:0,60',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
