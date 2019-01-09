<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;
use App\Models\Company;

class CompaniesAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/company.php
     * @var array
     */
    protected $translateAttributes = ['id', 'name', 'status'];
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
            'name' => 'nullable|string|max:100',
            'status' => 'nullable|in:' . implode(',', array_keys(Company::getStatuses())),
        ];
    }

    public function getTransTemplate()
    {
        return 'company.form.:attribute';
    }
}
