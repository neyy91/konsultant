<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;


class HonorsEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['honor'];

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
            'honor' => 'required|mimes:' . config('site.user.honor.mimes', 'jpg,jpeg') . '|max:' . config('site.user.honor.filesize', 500),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
