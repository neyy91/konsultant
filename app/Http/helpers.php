<?php 

if (!function_exists('asset2')) {
    function asset2($src) {
        return env('APP_ENV') == 'local' ? asset($src) : elixir($src, '');
    }
}

if (!function_exists('default_user_photo')) {
    function default_user_photo($user)
    {
        return str_replace(['{gender}'], [$user->gender ? $user->gender : 'none'], config('site.user.photo.default', '/storage/default/photo_{gender}.jpg'));
    }
}

