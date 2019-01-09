<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Document;
use App\Models\Pay;


class DocumentPolicy
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
     * Список документов.
     * @param  User   $user
     * @return boolean
     */
    public function listDoc(User $user)
    {
        return $user->status;
    }

    /**
     * Просмотр заказ документа.
     * @param  User     $user
     * @param  Document $document
     * @return boolean
     */
    public function view(User $user, Document $document)
    {
        return $user->can('admin', User::class)
            || $document->status !== Document::STATUS_BLOCKED && $user->can('provide', User::class) && $user->status // статус для тех, кто предоставляет услугу
            || $document->user_id == $user->id // владелец вопроса
        ;
    }

    /**
     * Доступ к заказам документов пользователя.
     * @param  User   $user
     * @return boolean
     */
    public function documentsUser(User $user)
    {
        return $user->can('admin', User::class)
            || $user->can('provide', User::class) && $user->status;
    }

    /**
     * Создание документа.
     * @param  User   $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isUser && $user->status;
    }

    /**
     * Может публиковать заказа документа.
     * @param  User     $user
     * @param  Document $document
     * @return boolean
     */
    public function publish(User $user)
    {
        return $this->create($user) && // может создать
            $user->documents->count() > 0 && $user->documents->filter(function($item) {
                return !in_array($item->status, [Document::STATUS_BLOCKED, Document::STATUS_UNPUBLISHED]);
            })->count() > 0 // есть вопросы и нормальные)
        ;
    }

    /**
     * Может видеть ответы.
     * @param  User     $user
     * @param  Document $document
     * @return boolean
     */
    public function answers(User $user, Document $document)
    {
        return $user->can('admin', User::class)
            || $user->id === $document->user_id && $document->payment
            || $user->status && $user->can('provide', User::class)
        ;
    }

    /**
     * Может оплатить.
     * @param  User     $user
     * @param  Document $document
     * @return boolean
     */
    public function pay(User $user, Document $document)
    {
        return $user->isUser && $user->status && $document->user_id == $user->id
            && $document->status ===  Document::STATUS_LAWYER_SELECTED
            && $document->payCost > 0 && $document->payment && $document->payment->status === Pay::STATUS_START;
    }

}
