<?php

namespace App\Http\Requests\Bookmark;

use App\Http\Requests\Request;

class BookmarkCategoryCreateRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['name', 'type', 'question', 'bookmark'];

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
            'name' => 'required|string|min:3|max:30',
            'type' => 'required|alpha_num',
            'question' => 'nullable|integer',
            'bookmark' => 'nullable|integer',
        ];
    }

    public function getTransTemplate()
    {
        return 'bookmark.form.category_:attribute';
    }
}
