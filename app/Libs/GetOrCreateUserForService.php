<?php 

namespace App\Libs;

use Auth;

use App\Models\User;


trait GetOrCreateUserForService {

    /**
     * Создание пользователя для услуг.
     * @param  mixed                $request
     * @param  string|null          $policy
     * @param  string|array|null    $class
     * @return array
     */
    public function getOrCreateUserForService($request, $policy = null, $class = null)
    {
        if (Auth::guest()) {
            $params = [
                'password' => str_random(6),
                'new_user' => true,
            ];
            $user = User::create($request->only(['firstname', 'email', 'telephone', 'city_id']) + ['password' => bcrypt($params['password'])]);
            Auth::attempt(['email' => $user->email, 'password' => $params['password']]);
        } else {
            if ($policy && $class) {
                $this->authorize($policy, $class);
            }
            $user = Auth::user();
            $telephone = $request->input('telephone');
            if ($telephone && !$user->telephone) {
                $user->telephone = $telephone;
            }
            $user->save();
            $params = [
                'new_user' => false,
            ];
        }

        return ['user' => $user, 'params' => $params];
    }

}