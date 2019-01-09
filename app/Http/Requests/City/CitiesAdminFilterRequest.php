<?php

namespace App\Http\Requests\City;

use App\Http\Requests\Request;

class CitiesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/city.php
     * @var array
     */
    protected $translateAttributes = ['id', 'name', 'region_id', 'status'];
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
            'name' => 'nullable|string|max:255',
            'status' => 'nullable|integer',
            'region_id' => 'nullable|integer',
        ];
    }

    public function getTransTemplate()
    {
        return 'city.form.:attribute';
    }
}
