<?php

namespace App\Http\Requests\Site;

use Auth;
use App\Http\Requests\Request;
use App\Models\Lawyer;

class ThankingRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['firstname', 'email', 'sum'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $lawyer = (new Lawyer)->getTable();
        
        return [
            'sum' => 'required|in:' . implode(',', config('site.lawyer.pay', [100, 200, 400, 900])),
            'lawyer' => "required|exists:{$lawyer},id",
        ] + (Auth::guest() ? [
            'url' => 'required|in:url',
            'firstname' => 'required|string|max:128',
            'email' => 'required|email',
        ] : []);
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
