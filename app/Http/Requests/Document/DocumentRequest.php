<?php

namespace App\Http\Requests\Document;

use Auth;
use Gate;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;


class DocumentRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document.php
     * @var array
     */
    protected $translateAttributes = ['document_type_id', 'cost', 'title', 'description', 'file', 'city_id', 'telephone'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest() ? true : Gate::allows('create', Document::class);
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
        $tableUser = (new User)->getTable();

        return [
            'document_type_id' => "required|exists:$tableDocumentType,id,status," . DocumentType::PUBLISHED,
            'cost' => 'nullable|integer|min:' . config('site.document.min_price', 800),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:' . config('site.document.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.document.file.max_size', 500) . '|mimes:' . config('site.document.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:$tableCity,id,status," . City::PUBLISHED,
            'telephone' => 'nullable|string|max:15',
        ] + (Auth::guest() ? [
            'firstname' => 'required|string|max:100',
            'email' => "required|email|unique:{$tableUser},email",
        ] : []);
    }

    public function getTransTemplate()
    {
        return 'document.form.:attribute';
    }
}
