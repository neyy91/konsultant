<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\CategoryLaw;


/**
 * Категории права.
 */
class CategoryLawPolicy
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
     * Может просмотреть категорию права.
     * @param  User        $user
     * @param  CategoryLaw $categoryLaw
     * @return boolean
     */
    public function view(User $user, CategoryLaw $categoryLaw)
    {
        return $user->can('admin', User::class)
            || $user->status && ($categoryLaw->status == CategoryLaw::PUBLISHED || ($categoryLaw->parent && $categoryLaw->parent->status == CategoryLaw::PUBLISHED))
        ;
    }
}
