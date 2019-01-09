<?php

namespace App\Http\Requests\Bookmark;

use App\Http\Requests\Request;

class BookmarkCreateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['id', 'category', 'question', 'from'];

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
            'category' => 'required|integer',
            'question' => 'required|integer',
            'from' => 'nullable|string|alpha_num',
        ];
    }

    public function getTransTemplate()
    {
        return 'bookmark.form.:attribute';
    }
}
