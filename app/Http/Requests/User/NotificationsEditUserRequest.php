<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Models\Notification;


class NotificationsEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['notifications'];

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
            'notifications.*' => 'nullable|in:' . implode(',', array_keys(Notification::getTypes())),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
