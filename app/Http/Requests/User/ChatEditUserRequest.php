<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;


class ChatEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['callavailable', 'telephone', 'timezone', 'weekdays', 'weekdaysfrom', 'weekdaysto', 'weekend', 'weekendfrom', 'weekendto', 'linebreak'];

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
            'callavailable' => 'nullable|boolean',
            'telephone' => 'nullable|string|max:15',
            'timezone' => 'nullable|in:' . implode(',', array_keys(trans('timezone'))),
            'weekdays' => 'nullable|boolean',
            'weekdaysfrom' => 'nullable|numeric|between:0,23.59',
            'weekdaysto' => 'nullable|numeric|between:0,23.59',
            'weekend' => 'nullable|boolean',
            'weekendfrom' => 'nullable|numeric|between:0,23.59',
            'weekendto' => 'nullable|numeric|between:0,23.59',
            'linebreak' => 'nullable|in:' . implode(',', array_keys(User::getLinebreaks())),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
