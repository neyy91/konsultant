<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

use App\Models\City;
use App\Models\User;
use App\Models\Lawyer;


class AdvancedEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['costcall', 'costchat', 'costdocument', 'cost', 'aboutmyself'];

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
            'costcall' => 'nullable|integer|min:' . config('site.call.min_price', 500),
            'costchat' => 'nullable|integer|min:' . config('site.chat.min_price', 500),
            'costdocument' => 'nullable|integer|min:' . config('site.document.min_price', 500),
            'cost' => 'nullable|string|max:' . config('site.global.max_text', 5000),
            'aboutmyself' => 'nullable|string|max:' . config('site.user.aboutmyself.max_string', 150),
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
