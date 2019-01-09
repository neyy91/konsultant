<?php

namespace App\Http\Requests\Theme;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;
use App\Models\CategoryLaw;
use App\Models\Theme;

class ThemeRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/theme.php
     * @var array
     */
    protected $translateAttributes = ['name', 'autoslug', 'slug', 'category_law_id', 'sort', 'status', 'description', 'text'];
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
        $statuses = implode(',', Theme::getStatusKeys());
        $table = (new Theme)->getTable();
        $tableCategoryLaw = (new CategoryLaw)->getTable();
        // Получение парметра Id роута и установка фильтра для Validation rules
        $params = $route->parameters();
        $slugFilter = '';
        $parentFilter = '';
        if (isset($params['id'])) {
            $slugFilter = ",{$params['id']}";
        }

        return [
            'name' => 'required|string|max:255',
            'autoslug' => 'nullable|boolean',
            'slug' => "required_unless:autoslug,1|nullable|string|alpha_dash|unique:{$table},slug{$slugFilter}",
            'category_law_id' => "nullable|exists:{$tableCategoryLaw},id",
            'sort' => 'nullable|integer',
            'status' => "nullable|in:{$statuses}",
            'description' => 'nullable|string',
            'text' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'theme.form.:attribute';
    }

    
}
