<?php

namespace App\Http\Requests\Document;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;
use App\Models\DocumentType;

class DocumentTypeRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document_type.php
     * @var array
     */
    protected $translateAttributes = ['name', 'autoslug', 'slug', 'parent_id', 'sort', 'status', 'description', 'text'];
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
    public function rules(Route $route)
    {
        $statuses = implode(',', DocumentType::getStatusKeys());
        $table = (new DocumentType)->getTable();
        // Получение парметра Id роута и установка фильтра для Validation rules
        $params = $route->parameters();
        $slugFilter = '';
        $parentFilter = '';
        if (isset($params['id'])) {
            $slugFilter = ",{$params['id']}";
            $parentFilter = ",id,!{$params['id']}";
        }

        return [
            'name' => 'required|string|max:255',
            'autoslug' => 'nullable|boolean',
            'slug' => "required_unless:autoslug,1|string|alpha_dash|unique:{$table},slug{$slugFilter}",
            'parent_id' => "nullable|exists:{$table},parent_id{$parentFilter}",
            'sort' => 'required|integer',
            'status' => "in:{$statuses}",
            'description' => 'nullable|string',
            'text' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'document_type.form.:attribute';
    }

    
}
