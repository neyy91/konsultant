<?php

namespace App\Http\Requests\Call;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\Call;

class CallUpdateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/call.php
     * @var array
     */
    protected $translateAttributes = ['status', 'title', 'description', 'file', 'city_id', 'telephone'];
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
        $statuses = implode(',', Call::getStatusKeys());

        return [
            'status' => "required|in:$statuses",
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:' . config('site.call.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.call.file.max_size', 500) . '|mimes:' . config('site.call.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:$tableCity,id,status," . City::PUBLISHED,
            'telephone' => 'required|string|max:15'
        ];
    }

    public function getTransTemplate()
    {
        return 'call.form.:attribute';
    }
}
