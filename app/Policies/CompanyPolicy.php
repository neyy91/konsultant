<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Company;


class CompanyPolicy
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

    /**
     * Может обновить документа.
     * @param  User    $user
     * @param  Company $company
     * @return boolean
     */
    public function update(User $user, Company $company)
    {
        return $user->status && $company->owner->count() > 0 && $company->owner->user->id == $user->id;
    }

    /**
     * Может видеть данных документ.
     * @param  User    $user
     * @param  Company $company
     * @return boolean
     */
    public function view(User $user, Company $company)
    {
        return $user->can('admin', User::class)
            || $company->status == Company::PUBLISHED
            || $user->can('company-owner', $company)
        ;
    }
}
