<?php

namespace App\Http\Requests\Site;

use App\Http\Requests\Request;


class FeedbackRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/feedback.php
     * @var array
     */
    protected $translateAttributes = ['theme', 'contact', 'text'];

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
        $isEmail = filter_var($this->input('contact'), FILTER_VALIDATE_EMAIL);
        return [
            'theme' => 'required|in:' .  implode(',', array_keys(trans('feedback.themes'))),
            'text' => 'required|string|max:' . config('site.global.max_text', 5000),
            'contact' => 'required|string|' . ($isEmail ? 'email' : 'max:100'),
        ];
    }

    public function getTransTemplate()
    {
        return 'feedback.form.:attribute';
    }

}
