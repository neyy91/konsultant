<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;


class EducationFileEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['file'];

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
            'file' => 'required|mimes:' . config('site.user.education.file.mimes', 'jpg,jpeg') . '|max:' . config('site.user.education.file.filesize', 500),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.education.:attribute';
    }

}
