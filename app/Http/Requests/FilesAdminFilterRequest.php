<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\File;

class FilesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/file.php
     * @var array
     */
    protected $translateAttributes = ['id', 'basename', 'mime_type', 'owner_type', 'owner_id'];
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
            'basename' => 'nullable|string|max:255',
            'mime_type' => 'nullable|string',
            'owner_type' => 'nullable|string',
            'owner_id' => 'nullable|integer',
        ];
    }

    public function getTransTemplate()
    {
        return 'file.form.:attribute';
    }

    
}
