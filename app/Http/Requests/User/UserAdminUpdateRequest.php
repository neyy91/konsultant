<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;


class UserAdminUpdateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['status', 'firstname', 'lastname', 'middlename', 'email', 'new_password', 'password'];

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
            'status' => 'required|boolean',
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'new_password' => 'nullable|string|min:6',
            'password' => 'required',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
