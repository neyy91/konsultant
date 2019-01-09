<?php 

namespace App\Events\Question;

use App\Models\User;
use App\Models\Question;


abstract class QuestionEvent
{

    /**
     * Вопрос.
     * @var Question
     */
    public $question;

    /**
     * Пользователь
     * @var User
     */
    public $user;

    /**
     * Параметры.
     * @var array
     */
    public $params;

    /**
     * Создание события.
     * @param Question $question
     * @param User     $user
     * @param array   $params
     */
    public function __construct(Question $question, User $user, array $params = [])
    {
        $this->question = $question;
        $this->user = $user;
        $this->params = $params;
    }

}