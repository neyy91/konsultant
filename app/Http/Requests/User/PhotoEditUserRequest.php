<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;


class PhotoEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['photo'];

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
            'photo' => 'required|mimes:' . config('site.user.photo.mimes', 'jpg,jpeg,png') . '|max:' . config('site.user.photo.filesize', 500),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
