<?php

namespace App\Http\Requests\User;

use Auth;

use App\Http\Requests\Request;
use App\Models\User;


class LawyerAdminUpdateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['companyname', 'companyowner', 'expert'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('admin', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyname' => 'nullable|string|max:255',
            'companyowner' => 'nullable|boolean',
            'expert' => 'nullable|boolean',
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
