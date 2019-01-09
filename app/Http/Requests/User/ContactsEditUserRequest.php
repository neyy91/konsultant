<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;


class ContactsEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['contactphones', 'contactemail', 'fax', 'site', 'skype'];

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
            'contactphones' => 'nullable|array|max:10',
            'contactphones.*' => 'nullable|string|max:15',
            'contactemail' => 'nullable|email|max:255',
            'fax' => 'nullable|string|max:15',
            'site' => 'nullable|url|max:255',
            'skype' => 'nullable|string|max:30',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
