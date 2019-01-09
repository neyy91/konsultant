<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Theme;


class ThemePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Theme $theme)
    {
        return $user->can('admin')
            || $user->status && $theme->status == Theme::PUBLISHED
        ;
    }
}
