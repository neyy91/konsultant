<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

use App\Models\City;
use App\Models\User;
use App\Models\Lawyer;


class PersonalEditUserRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/user.php
     * @var array
     */
    protected $translateAttributes = ['firstname', 'lastname', 'middlename', 'city', 'birthday', 'gender'];

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
        $user = \Auth::user();
        $in = array_map(function ($value) {
            return implode(',', array_keys($value));
        }, [
            'gender' => User::getGenders(),
            'status' => Lawyer::getStatuses(),
        ]);
        return [
            'firstname' => 'non_present',
            'lastname' => $user->lastname ? 'non_present' : 'nullable|string|max:255',
            'middlename' => $user->middlename ? 'non_present' : 'nullable|string|max:255',
            'city' => "nullable|exists:{$tableCity},id,status," . City::PUBLISHED,
            'status' => $user->isLawyer ? 'nullable|in:' . $in['status'] : 'non_present',
            'birthday' => "nullable|date",
            'telephone' => 'nullable|regex:/^[0-9]{11}$/',
            'gender' => 'nullable|in:' . $in['gender'],
        ];
    }

    public function getTransTemplate()
    {
        return 'user.form.:attribute';
    }

}
