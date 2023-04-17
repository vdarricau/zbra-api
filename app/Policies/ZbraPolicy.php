<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Zbra;

class ZbraPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Zbra $zbra): bool
    {
        return
            $zbra->receiver()->is($user) ||
            $zbra->sender()->is($user);
    }
}
