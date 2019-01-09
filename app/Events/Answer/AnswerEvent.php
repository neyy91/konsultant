<?php 

namespace App\Events\Answer;

use App\Models\Answer;
use App\Models\User;


abstract class AnswerEvent
{
    /**
     * Ответ.
     * @var Answer
     */
    public $answer;

    /**
     * Пользователь.
     * @var User
     */
    public $user;

    /**
     * Параметры.
     * @var array
     */
    public $params;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Answer $answer, User $user, array $params = [])
    {
        $this->answer = $answer;
        $this->user = $user;
        $this->params = $params;
    }
}