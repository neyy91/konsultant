<?php

namespace App\Http\Requests\Theme;

use App\Http\Requests\Request;

class ThemesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/theme.php
     * @var array
     */
    protected $translateAttributes = ['name', 'category_law_id', 'status'];

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
            'name' => 'nullable|string|max:255',
            'category_law_id' => "nullable|integer",
            'status' => 'nullable|integer',
            'order' => 'nullable|array',
            'order.*' => 'string',
        ];
    }

    public function getTransTemplate()
    {
        return 'theme.form.:attribute';
    }

}
