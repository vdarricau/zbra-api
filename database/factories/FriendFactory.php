<?php

namespace Database\Factories;

use App\Models\FriendRequest;
use App\Models\User;

class FriendFactory
{
    public static function make(User $user, array $attributes = []): User
    {
        /** @var User */
        $friend = User::factory()->create($attributes);

        $friendRequest = FriendRequest::factory()->create([
            'sender_user_id' => $user->id,
            'receiver_user_id' => $friend->id,
        ]);

        $friendRequest->accept();

        $friend->refresh();
        $user->refresh();

        return $friend;
    }
}
