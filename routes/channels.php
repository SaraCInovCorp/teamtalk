<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
    return $user->rooms->contains($roomId);
});

Broadcast::channel('chat.user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
