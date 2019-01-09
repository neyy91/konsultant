<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Answer;
use App\Models\Lawyer;


class ClarifyPolicy
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
     * Добавление уточнения для ответа.
     * @param  User     $user
     * @param  Answer $answer
     * @return boolean
     */
    public function clarifyAnswer(User $user, Answer $answer)
    {
        return $user->lawyer && $answer->from_type == Lawyer::MORPH_NAME && $answer->from_id === $user->lawyer->id // может создать и является владельцем
        ;
    }

    /**
     * Добавление уточнения.
     * @param  User     $user
     * @param  mixed $model
     * @return boolean
     */
    public function clarify(User $user, $model)
    {
        $class = get_class($model);
        return $user->can('create', $class) && $model->user_id && $model->user_id == $user->id // может создать и является владельцем
            && $model->status == $model::STATUS_IN_WORK // и вопрос в работе
        ;
    }
}
