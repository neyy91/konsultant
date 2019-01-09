<?php

namespace App\Listeners;

use Date;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Password;

use App\Repositories\UserRepository;
use App\Models\Subscribe;


/**
 * События пользователя.
 */
class UserEventSubscriber
{

    /**
     * Репозиторий пользователей.
     * @var UserRepository
     */
    protected $users;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function onUserLogin($event)
    {
        $user = $event->user;
        if (!$user->status) {
            return false;
        }
        $user->login_at = Date::now();
        $user->comet_key = Password::getRepository()->createNewToken();
        $user->save();
    }

    public function onUserLogout($event)
    {
        $user = $event->user;
        $user->logout_at = Date::now();
        $user->save();
    }

    public function onUserRegistered($event)
    {
        $this->users->initialNotificationsUser($event->user);
        // подписка на события для него
        // (new Subscribe())->user()->associate($event->user)->owner()->associate($event->user)->save();
    }

    public function subscribe($events)
    {
        $events->listen(\Illuminate\Auth\Events\Login::class, self::class . '@onUserLogin');
        $events->listen(\Illuminate\Auth\Events\Logout::class, self::class . '@onUserLogout');
        $events->listen(\Illuminate\Auth\Events\Registered::class, self::class . '@onUserRegistered');
    }

    
}
