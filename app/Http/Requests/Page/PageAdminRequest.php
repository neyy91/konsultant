<?php

namespace App\Http\Requests\Page;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;
use App\Models\Page;


/**
 * Страница для админа.
 */
class PageAdminRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document_type.php
     * @var array
     */
    protected $translateAttributes = ['status', 'title', 'autoslug', 'slug', 'description', 'layout', 'page_layout', 'text'];
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
        $table = (new Page)->getTable();
        $params = $route->parameters();
        $slugFilter = '';
        if (isset($params['id'])) {
            $slugFilter = ",{$params['id']}";
        }
        return [
            'status' => 'nullable|in:' . implode(',', Page::getStatusKeys()),
            'title' => 'nullable|string|max:100',
            'autoslug' => 'nullable|boolean',
            'slug' => "required_unless:autoslug,1|nullable|string|alpha_dash|unique:{$table},slug{$slugFilter}",
            'layout' => 'nullable|in:' . implode(',', array_keys(Page::getLyoutsList())),
            'page_layout' => 'nullable|in:' . implode(',', array_keys(Page::getPageLayoutsList())),
            'text' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'document_type.form.:attribute';
    }

    
}
