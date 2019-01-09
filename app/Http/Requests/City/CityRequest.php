<?php

namespace App\Http\Requests\City;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\Region;

class CityRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/city.php
     * @var array
     */
    protected $translateAttributes = ['name', 'region_id', 'region_new', 'autoslug', 'slug', 'sort', 'status', 'description', 'text'];
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
        $statuses = implode(',', City::getStatusKeys());
        $table = (new City)->getTable();
        $tableRegion = (new Region)->getTable();
        // Получение парметра Id роута и установка фильтра для Validation rules
        $params = $route->parameters();
        $slugFilter = '';
        if (isset($params['id'])) {
            $slugFilter = ",{$params['id']}";
        }

        return [
            'name' => 'required|string|max:255',
            'region_id' => "required_without:region_new|nullable|exists:$tableRegion,id",
            'region_new' => "required_without:region_id|nullable|string|max:255|unique:{$tableRegion},name",
            'autoslug' => 'nullable|boolean',
            'slug' => "required_unless:autoslug,1|string|alpha_dash|unique:{$table},slug{$slugFilter}",
            'sort' => 'nullable|integer',
            'status' => "nullable|in:{$statuses}",
            'description' => 'nullable|string',
            'text' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'city.form.:attribute';
    }
}
