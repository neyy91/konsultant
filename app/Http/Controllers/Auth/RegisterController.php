<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Переадресация.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectTo()
    {
        return route('user.dashboard', [], false);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.register');
    }

    /**
     * Проверка данных.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:100',
            'lastname' => 'required_if:as,' . User::TYPE_LAWYER .',' . User::TYPE_COMPANY . '|max:100',
            'company' => 'required_if:as,' . User::TYPE_COMPANY . '|max:100',
            'email' => 'required|email|max:255|unique:' . (new User)->getTable(),
            'password' => 'required|min:6|confirmed',
            'as' => 'in:' . User::TYPE_USER . ',' . User::TYPE_LAWYER . ',' . User::TYPE_COMPANY,
            'url' => 'required|in:url',
        ], trans('user.validation.register'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $noClient = $data['as'] != User::TYPE_USER;
        $userDatas = [
            'firstname' => $data['firstname'],
            'lastname' => $noClient ? $data['lastname'] : null,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];
        $user = User::create($userDatas);
        if ($noClient) {
            $user->lawyer()->create([]);
            if ($data['as'] == User::TYPE_COMPANY) {
                $user->lawyer->companyowner = true;
                $user->lawyer->companyname = $data['company'];
                $company = Company::create(['name' => $data['company']]);
                $user->lawyer->company()->associate($company);
                $user->lawyer->save();
            }
        }
        return $user;
    }

    /**
     * Пользователь зарегистрировался.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function registered(Request $request, $user)
    {
        if ($request->ajax()) {
            return response()->json([
                'redirect' => $this->redirectPath()
            ]);
        }
        return redirect($this->redirectPath());
    }

}
