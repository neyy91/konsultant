<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Like;
use App\Models\Answer;
use App\Models\Lawyer;
use App\Models\Question;


class LikePolicy
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
     * Может видеть лайки.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function likeThumbs(User $user, Answer $answer)
    {
        return $answer->to_type == Question::MORPH_NAME && $user->can('provide', User::class)
            || $answer->to && $answer->to_type != Answer::MORPH_NAME && $answer->to->user_id && $answer->to->user_id == $user->id; // владелец заказа
    }

    /**
     * Может оставить отзыв
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function likeAnswer(User $user, Answer $answer)
    {
        return $user->can('provide', User::class) && $user->status && $answer->to_type === Question::MORPH_NAME // предоставляет услугу
            && $answer->from_type === Lawyer::MORPH_NAME && $user->lawyer && $answer->from_id != $user->lawyer->id // но не владелец ответа
            && $answer->likes->where('user_id', $user->id)->count() == 0 // не оставлял еще отзыв
        ;
    }

    public function unlikeAnswer(User $user, Answer $answer)
    {
        return $answer->likes->where('user_id', $user->id)->count() > 0;
    }
}
