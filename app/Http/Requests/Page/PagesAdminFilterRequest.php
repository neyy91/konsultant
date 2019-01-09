<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\Request;

use App\Models\Page;


/**
 * Фильтрация страниц для админа.
 */
class PagesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document_type.php
     * @var array
     */
    protected $translateAttributes = ['id', 'title', 'status', 'layout', 'page_layout'];
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
            'title' => 'nullable|string|max:100',
            'status' => 'nullable|in:' . implode(',', Page::getStatusKeys()),
            'layout' => 'nullable|in:' . implode(',', array_keys(Page::getLyoutsList())),
            'page_layout' => 'nullable|in:' . implode(',', array_keys(Page::getPageLayoutsList())),
        ];
    }

    public function getTransTemplate()
    {
        return 'document_type.form.:attribute';
    }

    
}
