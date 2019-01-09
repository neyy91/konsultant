<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel(App\Events\Chat\ChatMessage::CHANNEL_PREFIX . '{key}', function (User $user, $key) {
   return $user->id == User::where('comet_key', '=', $key)->first()->id;
});

// Broadcast::channel('')