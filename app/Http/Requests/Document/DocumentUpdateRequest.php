<?php

namespace App\Http\Requests\Document;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\DocumentType;
use App\Models\Document;

class DocumentUpdateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document.php
     * @var array
     */
    protected $translateAttributes = ['status', 'sticky', 'document_type', 'title', 'description', 'file', 'city_id', 'telephone'];
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
        $tableCity = (new City)->getTable();
        $tableDocumentType = (new DocumentType)->getTable();
        $statuses = implode(',', Document::getStatusKeys());

        return [
            'status' => "required|in:$statuses",
            'sticky' => "nullable|boolean",
            'document_type_id' => "required|exists:$tableDocumentType,id,status," . DocumentType::PUBLISHED,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:' . config('site.question.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.question.file.max_size', 500) . '|mimes:' . config('site.question.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:$tableCity,id,status," . City::PUBLISHED,
            'telephone' => 'required|string|max:14'
        ];
    }

    public function getTransTemplate()
    {
        return 'document.form.:attribute';
    }
}
