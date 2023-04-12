<?php

namespace App\Models;

use App\Events\FriendRequestAcceptedEvent;
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

    public const STATUS_SENT = 'sent';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'accepted' => FriendRequestAcceptedEvent::class,
    ];

    /**
     * @return HasOne
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * @return HasOne
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function accept(): void
    {
        $this->update(['status' => self::STATUS_ACCEPTED]);
        $this->fireModelEvent('accepted');
    }

    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
        $this->fireModelEvent('cancelled');
    }

    public static function find(User $user, User $futureFriend): Builder
    {
        return 
            self::where('sender_user_id', $futureFriend->id)
                ->where('receiver_user_id', $user->id)
                ->union(
                    self::where('sender_user_id', $user->id)
                    ->where('receiver_user_id', $futureFriend->id)
                )
        ;
    }

    public static function exists(User $user, User $futureFriend): bool
    {
        return self::find($user, $futureFriend)->count() !== 0;
    }
}
