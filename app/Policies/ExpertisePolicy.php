<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Lawyer;
use App\Models\Expertise;
use App\Models\Question;


class ExpertisePolicy
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
     * Может писать сообщение
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function message(User $user, Question $question)
    {
        return $user->can('admin', User::class)
            || $user->can('provide', User::class) && $user->status && $question->expertiseExperts && $question->expertiseExperts->where('lawyer_id', $user->lawyer->id)->count() > 0;
    }

    /**
     * Просмотр экспертизы.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function expertises(User $user, Question $question)
    {
        return $user->can('admin', User::class)
            || ($question->status == Question::STATUS_EXPERTISE) // статус - экспертиза
                && $this->message($user, $question) // может писать экспертизу
                || ($user->isLawyer && $user->status && $question->answers->filter(function($item) use ($user) {
                        return $item->from_type == Lawyer::MORPH_NAME && $item->from_id == $user->lawyer->id;
                    })->count() == 1 // или один из юристов, который написал ответ на вопрос
                )
        ;
    }

    public function expertiseClose(User $user, Question $question)
    {
        $owner = $question->expertiseOwner;
        return $owner && $user->lawyer && $owner->lawyer->id == $user->lawyer->id;
    }

}
