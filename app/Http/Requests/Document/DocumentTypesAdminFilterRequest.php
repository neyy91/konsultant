<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Request;

class DocumentTypesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document_type.php
     * @var array
     */
    protected $translateAttributes = ['name', 'parent_id', 'status'];
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
            'parent_id' => 'nullable|integer',
            'status' => 'nullable|integer',
        ];
    }

    public function getTransTemplate()
    {
        return 'document_type.form.:attribute';
    }

    
}
