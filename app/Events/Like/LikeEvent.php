<?php 

namespace App\Events\Like;

use App\Models\User;
use App\Models\Like;


abstract class LikeEvent
{

    /**
     * Вопрос.
     * @var Like
     */
    public $like;

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
     * @param Like $like
     * @param User     $user
     * @param array   $params
     */
    public function __construct(Like $like, User $user, array $params = [])
    {
        $this->like = $like;
        $this->user = $user;
        $this->params = $params;
    }

}