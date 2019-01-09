<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Lawyer;


class ComplaintPolicy
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
     * Нет жалоб от пользователя.
     * @param  User   $user
     * @param  mixed $model
     * @return boolean
     */
    public function noComplaints(User $user, $model)
    {
        return $model->complaints->filter(function($item) use ($model, $user) {
            return $item->against_type == $model::MORPH_NAME && $item->against_id = $model->id && $item->user_id == $user->id;
        })->count() == 0;
    }

    /**
     * Может отправить жалобу.
     * @param  User     $user
     * @param  mixed $model
     * @return boolean
     */
    public function complain(User $user, $model)
    {
        return ($user->can('admin', User::class)
            || $user->status && ( $model->user_id && $user->id != $model->user_id || $user->isLawyer && $model instanceof Answer && $model->from_type == Lawyer::MORPH_NAME && $user->lawyer->id != $model->from_id) // активный пользователь и не владелец
                && $user->can('provide', User::class) // также предлогает услуги
            )
            &&  $this->noComplaints($user, $model) // также нет жалобы
        ;
    }

    /**
     * Может отправить жалобу на вопрос.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function complainAnswer(User $user, Answer $answer)
    {
        return ($user->can('admin', User::class)
            || $user->status // активный
                && $model->from_type == Lawyer::MORPH_NAME && $user->lawyer->id != $model->from_id // не владелец
                && $user->can('provide', User::class) // также прдоставляет услугу
            )
            &&  $this->noComplaints($user, $model) // также нет жалобы
        ;
    }
}
