<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\Request;

class CompanyRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/company.php
     * @var array
     */
    protected $translateAttributes = ['name', 'logo', 'description', 'text'];
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
            'name' => 'required|string|max:100',
            'logo' => 'nullable|file|max:' . config('site.company.logo.max_size', 500) . '|mimes:' . config('site.company.logo.mimes', 'jpg,jpeg,png'),
            'description' => 'nullable|string|max:255',
            'text' => "nullable|string|max:5000",
        ];
    }

    public function getTransTemplate()
    {
        return 'company.form.:attribute';
    }
}
