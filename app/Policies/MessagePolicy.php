<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Message $message): bool
    {
        return
            $message->receiver()->is($user) ||
            $message->sender()->is($user);
    }
}
