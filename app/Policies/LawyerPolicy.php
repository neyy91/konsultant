<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Lawyer;

/**
 * Политика для юристов.
 */
class LawyerPolicy
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
     * Для владельцов компании.
     * @param  User   $user
     * @return boolean
     */
    public function companyOwner(User $user)
    {
        return $user->status && $user->isCompany && $user->lawyer->companyowner && $user->lawyer->company;
    }

    /**
     * Для владельцев компании со статусом активный.
     * @param  User   $user
     * @return boolean
     */
    public function companyOwnerActive(User $user)
    {
        return $this->companyOwner($user) // активный и владелец компании
            && $user->lawyer->company && $user->lawyer->company->status // статус у компании активный
        ;
    }

    /**
     * Для сотрудников.
     * @param  User   $user
     * @return boolean
     */
    public function employee(User $user)
    {
        return $this->status && $user->isLawyer // активный юрист
            && !$this->companyOwner($user) // не владелец компании
            && $user->lawyer->company // но относится к компании
        ;
    }

}
