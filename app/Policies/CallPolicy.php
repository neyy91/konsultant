<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Call;
use App\Models\Pay;
use App\Models\Answer;

class CallPolicy
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
     * Список звонков.
     * @param  User   $user
     * @return boolean
     */
    public function listCall(User $user)
    {
        return $user->status;
    }

    /**
     * Просмотр заказа звонка.
     * @param  User     $user
     * @param  Call $call
     * @return boolean
     */
    public function view(User $user, Call $call)
    {
        $answerIs = $call->answers()->where('is', 1)->first();
        return $user->can('admin', User::class)
            || in_array($call->status, [Call::STATUS_IN_WORK, Call::STATUS_UNPUBLISHED]) && $user->can('provide', User::class) && $user->status // статус для тех, кто предоставляет услугу
            || $call->user_id === $user->id // владелец вопроса
            || $call->pay && $call->pay === Call::PAY_FREE
            || $call->status !== Call::STATUS_BLOCKED && $user->can('lawyer-is', [Answer::class, $call])
        ;
    }

    /**
     * Доступ к звонкам пользователя.
     * @param  User   $user
     * @return boolean
     */
    public function callsUser(User $user)
    {
        return $user->can('admin', User::class)
            || $user->status && $user->can('provide', User::class);
    }

    /**
     * Создание вопроса.
     * @param  User   $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isUser && $user->status;
    }

    /**
     * Может публиковать заказ звонка.
     * @param  User   $user
     * @param  Call   $call
     * @return boolean
     */
    public function publish(User $user)
    {
        return $this->create($user) && // может создать
            $user->calls->count() > 0 && $user->calls->filter(function($item) {
                return !in_array($item->status, [Call::STATUS_BLOCKED, Call::STATUS_UNPUBLISHED]);
            })->count() > 0 // есть вопросы и нормальные)
        ;
    }

    /**
     * Ответы.
     * @param  User   $user
     * @param  Call   $call
     * @return boolean
     */
    public function answers(User $user, Call $call)
    {
        return $user->can('admin', User::class)
            || $call->pay === Call::PAY_FREE
            || $user->id === $call->user_id && $call->payment && $call->payment->status === Pay::STATUS_PAYED
            || $user->status && $user->can('provide', User::class)
        ;
    }

    /**
     * Может оплатить.
     * @param  User     $user
     * @param  Call $call
     * @return boolean
     */
    public function pay(User $user, Call $call)
    {
        return $user->isUser && $user->status
            && $call->user_id === $user->id
            && in_array($call->status, [Call::STATUS_UNPUBLISHED, Call::STATUS_IN_WORK])
            && $call->payCost > 0 && $call->payment && $call->payment->status === Pay::STATUS_START
        ;
    }

    /**
     * Контакты клиента.
     * @param  User   $user
     * @param  Call   $call
     * @return boolean
     */
    public function contacts(User $user, Call $call)
    {
        $answerIs = $call->answers()->where('is', 1)->first();
        return $user->can('provide', User::class) && $user->status && $call->status === Call::STATUS_LAWYER_SELECTED && $user->can('lawyer-is', [Answer::class, $call]);
    }

}
