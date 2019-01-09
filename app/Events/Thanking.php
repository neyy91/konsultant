<?php 

namespace App\Events;

use App\Models\User;

/**
 * Кто-то облагодарил.
 */
class Thanking extends Event
{
    /**
     * Пользователь
     * @var User
     */
    public $user;

    /**
     * Сумма.
     * @var integer
     */
    public $sum;

    /**
     * Создание события.
     * @param User     $user
     * @param integer  $sum
     */
    public function __construct(User $user, $sum)
    {
        $this->user = $user;
        $this->sum = $sum;
    }
}