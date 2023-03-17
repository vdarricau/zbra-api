<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendRequest extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public const REQUESTED = 'requested';
    public const PENDING = 'pending';

    /**
     * @return HasOne
     */
    public function requester(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'requester_id');
    }


    /**
     * @return HasOne
     */
    public function friendToBe(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'friend_id');
    }
}
