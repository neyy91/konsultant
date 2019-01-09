<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Request;
use App\Models\Document;

class DocumentsAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document.php
     * @var array
     */
    protected $translateAttributes = ['id', 'title', 'document_type_id', 'city_id', 'status'];

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
        $statuses = implode(',', Document::getStatusKeys());
        return [
            'id' => 'nullable|integer',
            'title' => 'nullable|string|max:255',
            'document_type_id' => "nullable|integer",
            'city_id' => "nullable|integer",
            'status' => 'nullable|integer',
            'sticky' => 'nullable|boolean',
            'order' => 'nullable|array',
            'order.*' => 'string',
        ];
    }

    public function getTransTemplate()
    {
        return 'document.form.:attribute';
    }

}
