<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Role;
use App\Models\Chat;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Репозиторий пользователя.
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Администратор.
     * @param  User   $user
     * @return boolean
     */
    public function admin(User $user)
    {
        return $user->status && $user->isAdmin;
    }

    /**
     * Доступ к пункт редактирования.
     * @param  User   $user
     * @param  string $edit
     * @return boolean
     */
    public function edit(User $user, $edit)
    {
        $editList = $this->users->getEditList($user->type);
        return $user->status && in_array($edit, $editList);
    }

    /**
     * Информация о пользователе.
     * @param  User   $user
     * @return boolean
     */
    public function userInfo(User $user)
    {
        return $this->userInfo($user); // TODO: больше проверок
    }

    /**
     * Просмотр небольшой информации о пользователе.
     * @param  User   $user
     * @return boolean
     */
    public function smallInfo(User $user)
    {
        return $this->admin($user) || $user->status && $this->provide($user);
    }

    /**
     * Предоставляют услугу.
     * @param  User   $user
     * @return boolean
     */
    public function provide(User $user)
    {
        return $user->isLawyer || $user->isCompany;
    }

    /**
     * Пользователь предоставляет услугу.
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function provideUser(User $me, User $user)
    {
        return $this->provide($user);
    }

    /**
     * Доступ к неопубликованным данным.
     * @param  User   $user
     * @return boolean       
     */
    public function unpublished(User $user)
    {
        return $user->can('admin', User::class)
            || ($user->status && $user->can('provide', User::class));
    }

    /**
     * Клиент.
     * @param  User   $user
     * @return boolean
     */
    public function client(User $user)
    {
        return $user->status && $user->isUser;
    }

    /**
     * Чат с пользователем.
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function chat(User $me, User $user)
    {
        return $me->can('access', [Chat::class, $user]);
    }

}
