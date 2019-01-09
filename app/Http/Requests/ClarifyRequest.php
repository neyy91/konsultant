<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ClarifyRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/clarify.php
     * @var array
     */
    protected $translateAttributes = ['text', 'file'];

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
            'text' => 'required|string|max:' . config('site.clarify.text_max', 5000),
            'file' => 'nullable|file|max:' . config('site.clarify.file.max_size', 500) . '|mimes:' . config('site.clarify.file.mimes', 'txt,doc,pdf'),
        ];
    }

    public function getTransTemplate()
    {
        return 'clarify.form.:attribute';
    }
}
