<?php

namespace App\Policies;

Use \Date;
use \Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Question;
use App\Models\Lawyer;
use App\Models\Answer;
use App\Models\Clarify;
use App\Models\Complaint;
use App\Models\Pay;

class QuestionPolicy
{
    use HandlesAuthorization;

    protected static $statuses = [
        'minimal' => [Question::STATUS_IN_WORK, Question::STATUS_LAWYER_SELECTED, Question::STATUS_COMPLETED],
        'for_lawyer' => [Question::STATUS_IN_WORK, Question::STATUS_LAWYER_SELECTED, Question::STATUS_COMPLETED, Question::STATUS_UNPUBLISHED, Question::STATUS_EXPERTISE],
    ];

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
     * Вопросы пользователя.
     * @param  User   $user
     * @return boolean
     */
    public function questionsUser(User $user)
    {
        return $user->can('admin', User::class)
            || $user->status && $user->can('provide', User::class);
    }

    /**
     * Просмотр для неавторизованных пользователей.
     * @param  Question $question
     * @return boolean
     */
    public static function viewMinimal(Question $question)
    {
        return in_array($question->status, self::$statuses['minimal']) && $question->pay === Question::PAY_FREE;
    }

    /**
     * Просмотр вопроса.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function view(User $user, Question $question)
    {
        return
            $user->can('admin', User::class)
            || self::viewMinimal($question) && $user->isUser // статус бесплатного вопроса для пользователя
            || in_array($question->status, self::$statuses['for_lawyer']) && $user->can('provide', User::class) // статус для тех, кто предоставляет услугу
            || $question->user_id == $user->id // владелец вопроса
        ;
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
     * Может публиковать вопрос.
     * @param  User   $user
     * @return boolean
     */
    public function publish(User $user)
    {
        $normalQuestions = $user->questions->filter(function($item) {
            return !in_array($item->status, [Question::STATUS_BLOCKED, Question::STATUS_UNPUBLISHED]);
        });
        return $this->create($user) && // может создать
            $user->questions->count() > 0 && $normalQuestions->count() > 0 // есть вопросы и нормальные)
        ;
    }

    /**
     * Могут обновить вопрос.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function update(User $user, Question $question)
    {
        return
            $user->can('admin', User::class)
            || $this->create($user) && $question->user_id == $user->id // Может создать и является владельцем
                && $question->created_at->diff(Date::now())->s < config('site.question.update_time', 180); // и не прошло больше {site.question.update_time} секунд
    }

    /**
     * Может отправить на экспертизу вопрос.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function onexpertise(User $user, Question $question)
    {
        return 
            $user->can('admin', User::class)
            || $user->can('provide', User::class) // юрист или компания
                && $user->status // также активный пользователь
                && $question->status != Question::STATUS_EXPERTISE && $question->status == Question::STATUS_LAWYER_SELECTED // также статус у вопроса правильный
                && $question->answers->where('from_id', $user->lawyer->id)->count() != 0 // также написал ответ
                && $question->expertiseOwner->count() == 0  // но не отправлен на экспертизу
        ;
    }

    /**
     * Может видеть ответы.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function answers(User $user, Question $question)
    {
        return $user->can('admin', User::class)
            || $question->pay === Question::PAY_FREE
            || $user->id === $question->user_id && $question->payment && $question->payment->status === Pay::STATUS_PAYED
            || $user->status && $user->can('provide', User::class)
        ;
    }

    /**
     * Может оплатить.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function pay(User $user, Question $question)
    {
        return $user->isUser && $user->status
            && $question->user_id === $user->id
            && in_array($question->status, [Question::STATUS_UNPUBLISHED, Question::STATUS_IN_WORK])
            && $question->payCost > 0 && $question->payment && $question->payment->status === Pay::STATUS_START
        ;
    }

}
