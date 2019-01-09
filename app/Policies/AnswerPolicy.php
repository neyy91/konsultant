<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Lawyer;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Pay;


class AnswerPolicy
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
     * Может ответить на заказа клиента.
     * @param  User     $user
     * @param  mixed $model
     * @return boolean
     */
    public function answer(User $user, $model)
    {
        return $user->can('provide', User::class) && $user->status // активный юрист или компания
            && in_array($model->status, [$model::STATUS_IN_WORK, $model::STATUS_UNPUBLISHED])
            && ($model->answers->count() == 0 || $model->answers->filter(function ($item) use ($user) {
                return $item->from_type == Lawyer::MORPH_NAME && $item->from_id == $user->lawyer->id;
            })->count() == 0)  // и нет ответов или нет ответа данного юриста
        ;
    }

    /**
     * Может ответить на ответ юриста(владелец вопроса).
     * @param  User     $user
     * @param  Answer $answer
     * @return boolean
     */
    public function reply(User $user, Answer $answer)
    {
        return $answer->to_type !== Answer::MORPH_NAME   // не ответ пользователя
            && $user->isUser && $user->status && $answer->to->user_id === $user->id // активный владелец вопроса
            && $answer->to->status === $answer->to::STATUS_IN_WORK; // статус
    }

    /**
     * Обновление ответа юристом.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function update(User $user, Answer $answer)
    {
        return $answer->to_type == Question::MORPH_NAME // вопрос
            && $user->lawyer && $answer->from_type == Lawyer::MORPH_NAME && $answer->from_id == $user->lawyer->id // владелец вопроса
            && $answer->to->status == $answer->to::STATUS_IN_WORK && !$answer->is; // статус и не выбран ответ
    }

    /**
     * Уточнения к ответу.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function clarify(User $user, Answer $answer)
    {
        return $user->status && $answer->from_type == Lawyer::MORPH_NAME && $answer->from_id == $user->lawyer->id;
    }

    /**
     * Может быть выбран исполнитель.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function answerIs(User $user, Answer $answer)
    {
        $func = 'isFor' . ucfirst(class_basename($answer->to));
        return method_exists($this, $func) ? $this->$func($user, $answer) : false;
    }

    /**
     * Выбор исполнителя для вопроса.
     * @param  User    $user
     * @param  Answer  $answer
     * @return boolean
     */
    public function isForQuestion(User $user, Answer $answer)
    {
        $question = $answer->to;
        return $user->isUser && $user->status && $question->user_id === $user->id && in_array($question->status, [Question::STATUS_IN_WORK, Question::STATUS_LAWYER_SELECTED]) && !$answer->is
            // && ($question->status === Question::STATUS_IN_WORK || $question->pay === Question::PAY_FREE) 
            || $question->status == Question::STATUS_EXPERTISE && $user->can('admin', User::class);
    }

    /**
     * Выбор исполнителя для документа.
     * @param  User    $user
     * @param  Answer  $answer
     * @return boolean
     */
    public function isForDocument(User $user, Answer $answer)
    {
        $document = $answer->to;
        return $user->isUser && $user->status && $document->user_id === $user->id
            && $document->status === Document::STATUS_IN_WORK
            && $user->cant('pay', $document)
            && $document->answers->where('is', true)->count() === 0
        ;
    }

    /**
     * Выбор юриста для звонка.
     * @param  User    $user
     * @param  Answer  $answer
     * @return boolean
     */
    public function isForCall(User $user, Answer $answer)
    {
        $call = $answer->to;
        return $user->isUser && $user->status && $call->user_id === $user->id
            && $call->status === Call::STATUS_IN_WORK
            && $user->cant('pay', $call)
        ;
    }

    /**
     * Доступ к контенту ответа.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function answerContent(User $user, Answer $answer)
    {
        $private = in_array($answer->to_type, [Document::MORPH_NAME, Call::MORPH_NAME]);
        return $user->can('admin', User::class)
            || $private && $user->status && $user->lawyer && $answer->from_type == Lawyer::MORPH_NAME && $answer->from_id == $user->lawyer->id // владелец ответа
            || $user->isUser && $user->status && $answer->to->user_id && $answer->to->user_id == $user->id // активный владелец заказа
            || $answer->to_type == Question::MORPH_NAME
        ;
    }

    /**
     * Доступ к ответу для гостя
     * @param  Answer $answer
     * @return boolean
     */
    public static function answerContentGuest(Answer $answer)
    {
        $private = in_array($answer->to_type, [Document::MORPH_NAME, Call::MORPH_NAME]);
        return !$private;
    }

    /**
     * Ответы пользователя
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function replies(User $user, Answer $answer)
    {
        return $user->status && (
            $answer->to->user_id === $user->id // активный владелец заказа
            || $answer->from_type === Lawyer::MORPH_NAME && $answer->from && $answer->from->user->id === $user->id // владелец ответа
            )
        ;
    }

    /**
     * Уточения юриста.
     * @param  User   $user
     * @param  Answer $answer
     * @return boolean
     */
    public function clarifies(User $user, Answer $answer)
    {
        return $user->status && (
            $answer->to->user_id === $user->id // активный владелец заказа
            || $answer->from_type === Lawyer::MORPH_NAME && $answer->from && $answer->from->user->id === $user->id // владелец ответа
            )
        ;
    }

    public function lawyerIs(User $user, $service)
    {
        $answerIs = $service->answers()->where('is', 1)->first();
        return $answerIs && $answerIs->from && $answerIs->from->user && $user->id === $answerIs->from->user->id;
    }

}
