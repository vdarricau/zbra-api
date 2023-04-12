<?php

namespace App\Policies;

use App\Models\FriendRequest;
use App\Models\User;

class FriendRequestPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FriendRequest $friendRequest): bool
    {
        return 
            $friendRequest->sender()->is($user) || 
            $friendRequest->receiver()->is($user)
        ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FriendRequest $friendRequest): bool
    {
        return 
            $friendRequest->sender()->is($user) || 
            $friendRequest->receiver()->is($user)
        ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FriendRequest $friendRequest): bool
    {
        return 
            $friendRequest->sender()->is($user) || 
            $friendRequest->receiver()->is($user)
        ;
    }
}
