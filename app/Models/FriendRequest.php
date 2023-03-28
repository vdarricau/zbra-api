<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return HasOne
     */
    public function friendToBe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    public static function find(User $user, User $futureFriend): Builder
    {
        return 
            self::where('requester_id', $futureFriend->id)
                ->where('friend_id', $user->id)
                ->union(
                    self::where('requester_id', $user->id)
                    ->where('friend_id', $futureFriend->id)
                )
        ;
    }

    public static function exists(User $user, User $futureFriend): bool
    {
        return self::find($user, $futureFriend)->count() !== 0;
    }
}
