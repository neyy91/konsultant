<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Атрибуты для перевода.
     * @var array
     */
    protected $translateAttributes = [];

    /**
     * Получение шаблона для перевода.
     * @return string
     */
    abstract public function getTransTemplate();

    public function attributes()
    {
        $transTempate = $this->getTransTemplate();
        $attributes = [];
        foreach ($this->translateAttributes as $attribute) {
            $attributes[$attribute] = trans(str_replace(':attribute', $attribute, $transTempate));
        }
        return $attributes;
    }
}
