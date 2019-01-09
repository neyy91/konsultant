<?php

namespace App\Http\Requests\Call;

use Auth;
use Gate;

use App\Http\Requests\Request;
use App\Models\City;
use App\Models\Call;
use App\Models\User;


class CallRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/call.php
     * @var array
     */
    protected $translateAttributes = ['title', 'description', 'file', 'city_id', 'firstname', 'telephone', 'email'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guest() ? true : Gate::allows('create', Call::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $tableCity = (new City)->getTable();
        $tableUser = (new User)->getTable();
        $pays = implode(',', Call::getPayKeys());

        return [
            'title' => 'required|string|max:255',
            'description' => 'max:' . config('site.call.max_text', 5000),
            'file' => 'nullable|file|max:' . config('site.call.file.max_size', 500) . '|mimes:' . config('site.call.file.mimes', 'txt,doc,pdf'),
            'city_id' => "required|exists:{$tableCity},id,status," . City::PUBLISHED,
            'telephone' => 'required|integer|min:10000000000|max:99999999999',
            'pay' => "required|in:{$pays}",
        ] + (Auth::guest() ? [
            'firstname' => 'required|string|max:100',
            'email' => "required|email|unique:{$tableUser},email",
        ] : []);
    }

    public function getTransTemplate()
    {
        return 'call.form.:attribute';
    }

    public function messages()
    {
        return trans('call.validation.messages');
    }
}
