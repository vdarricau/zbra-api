<?php

namespace App\Models;

use App\Events\ZbraCreatedEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zbra extends Model
{
    use HasFactory, HasUuids;

    public const FILTER_SENT = 'sent';
    public const FILTER_RECEIVED = 'received';

    public const STATUS_SENT = 'read';
    public const STATUS_RECEIVED = 'received'; // @TODO figure out this one
    public const STATUS_READ = 'read';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => ZbraCreatedEvent::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public static function getExchangedZbras(User $user, User $friend): Builder
    {
        return 
            Zbra::whereIn('sender_user_id', [$user->id, $friend->id])
            ->whereIn('receiver_user_id', [$user->id, $friend->id])
        ;
    }

    public static function getCountUnreadZbras(User $user, User $friend): int
    {
        return
            Zbra::where('sender_user_id', $friend->id)
            ->where('receiver_user_id', $user->id)
            ->whereNot('status', Zbra::STATUS_READ)
            ->count()
        ;
    }
}
