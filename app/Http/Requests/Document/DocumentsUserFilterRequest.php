<?php

namespace App\Http\Requests\Document;

use Illuminate\Routing\Route;

use App\Http\Requests\Request;


class DocumentsUserFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/document.php
     * @var array
     */
    protected $translateAttributes = ['city_id'];

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
            'city_id' => "nullable|integer",
            'perpage' => 'nullable|in:' . implode(',', config('site.user.perpages', [10,20,50,100])),
        ];
    }

    public function getTransTemplate()
    {
        return 'document.form.:attribute';
    }

}
