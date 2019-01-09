<?php 

namespace App\Events\Clarify;

use App\Models\User;
use App\Models\Clarify;


abstract class ClarifyEvent
{

    /**
     * Уточнение.
     * @var Clarify
     */
    public $clarify;

    /**
     * Уточнение для.
     * @var mixed
     */
    public $parent;

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
     * @param Clarify  $clarify
     * @param User     $user
     * @param mixed    $target
     * @param array    $params
     */
    public function __construct(Clarify $clarify, $parent, User $user, array $params = [])
    {
        $this->clarify = $clarify;
        $this->parent = $parent;
        $this->user = $user;
        $this->params = $params;
    }

}