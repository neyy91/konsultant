<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\User;


class UsersAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['id', 'status', 'firstname', 'lastname', 'type', 'city'];

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
            'id' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'type' => 'nullable|in:' . implode(',', array_keys(User::getTypes())),
            'city' => 'nullable|integer',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
