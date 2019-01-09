<?php

namespace App\Http\Middleware;

use Closure;
use Date;
use Auth;

/**
 * Обновление последнего доступа.
 */
class LastTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $user = $request->user();
        if ($user) {
            $user->last_time = Date::now();
            $user->save();
        }
    }
}
