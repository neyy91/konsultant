<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Chat;


class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Может писать сообщения.
     * @param  User   $user
     * @return boolean
     */
    public function chat(User $user)
    {
        return $user->status;
    }

    /**
     * Начало чата.
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function access(User $me, User $user)
    {
        return $user->status && $me->status // активные пользователи
            && !($me->isUser && $user->isUser) // кроме чата между клиентами
            && $me->id != $user->id;
        ;
    }

    /**
     * Удаление диалога чата
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function delete(User $me, Chat $chat)
    {
        return $chat && $chat->from_id == $me->id;
    }

    /**
     * Удаление всего чата.
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function deleteAll(User $me, User $user)
    {
        return $me->staus && $user->status && Chat::outgoing($me, $user)->onlyDialogs()->count() != 0;
    }

    /**
     * Может отправить сообщение.
     * @param  User   $me
     * @param  User   $user
     * @return boolean
     */
    public function message(User $me, User $user)
    {
        return $me->chats()->onlyDialogs()->where('to_id', '=', $user->id)->count() != 0 // есть диалог
        ;
    }

}
