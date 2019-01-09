<?php

namespace App\Http\Requests\Call;

use App\Http\Requests\Request;

class CallsAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/call.php
     * @var array
     */
    protected $translateAttributes = ['id', 'title', 'city_id', 'status'];

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
            'title' => 'nullable|string|max:255',
            'city_id' => "nullable|integer",
            'status' => "nullable|integer",
            'order' => 'nullable|array',
            'order.*' => 'string',
        ];
    }

    public function getTransTemplate()
    {
        return 'call.form.:attribute';
    }

}
