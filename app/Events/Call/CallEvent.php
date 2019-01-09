<?php 

namespace App\Events\Call;

use App\Models\User;
use App\Models\Call;


abstract class CallEvent
{

    /**
     * Вопрос.
     * @var Call
     */
    public $call;

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
     * @param Call $call
     * @param User     $user
     * @param array   $params
     */
    public function __construct(Call $call, User $user, array $params = [])
    {
        $this->call = $call;
        $this->user = $user;
        $this->params = $params;
    }

}