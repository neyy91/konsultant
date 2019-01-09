<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

/**
 * Обратная связь.
 */
class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Пользователь.
     * @var User
     */
    public $user;

    /**
     * Данные.
     * @var array
     */
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.site.feedback')
            ->subject($this->data['theme']);
    }
}
