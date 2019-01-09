<?php 

namespace App\Events\Complaint;

use App\Models\User;
use App\Models\Complaint;


abstract class ComplaintEvent
{

    /**
     * Уточнение.
     * @var Complaint
     */
    public $complaint;

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
     * @param Complaint  $complaint
     * @param User     $user
     * @param mixed    $target
     * @param array    $params
     */
    public function __construct(Complaint $complaint, User $user, array $params = [])
    {
        $this->complaint = $complaint;
        $this->user = $user;
        $this->params = $params;
    }

}