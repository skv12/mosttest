<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\Chat;
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

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('chat.{id}', Chat::class);
Broadcast::channel('user.chats', Chat::class);
Broadcast::channel('users.status', Chat::class);
Broadcast::channel('message.seen', Chat::class);
