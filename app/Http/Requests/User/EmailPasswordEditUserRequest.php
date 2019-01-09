<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;


class EmailPasswordEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['email', 'new_password', 'new_password_confirmation', 'current_password'];

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
            'email' => 'nullable|email',
            'new_password' => 'nullable|string|min:6|confirmed',
            'current_password' => 'required',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
