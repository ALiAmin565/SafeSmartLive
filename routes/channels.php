<?php

use App\Models\User;
use App\Broadcasting\recommend;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{plan_name}', function () {
    return true;
});


Broadcast::channel('recommendation.{plan_name}', function ($user, $plan_name) {
    // You can add custom logic here to authorize the user to listen to the channel
    return true;
});
Broadcast::channel('closeChat.{plan_name}', function () {
     return true;
});




