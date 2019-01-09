<?php

namespace App\Http\Requests\CategoryLaw;

use App\Http\Requests\Request;

class CategoriesLawAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/category.php
     * @var array
     */
    protected $translateAttributes = ['id', 'name', 'parent_id', 'status'];

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
            'parent_id' => "nullable|integer",
            'status' => "nullable|integer",
            'order' => 'nullable|array',
            'order.*' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'category.form.:attribute';
    }

}
