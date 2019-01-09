<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

/**
 * Письмо после регистрации.
 */
class Registered extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Пользователь.
     * @var User
     */
    public $user;

    /**
     * Параметры.
     * @var array
     */
    public $params = [];

    /**
     * Создания сообщения.
     *
     * @return void
     */
    public function __construct(User $user, array $params)
    {
        $this->user = $user;
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('eamil.user.registered')
            ->subject(trans('user.registered_success'));
    }
}
