<?php

namespace App\Models;

use App\Events\MessageSentEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $status
 * @property string $message
 */
class Message extends Model
{
    use HasFactory, HasUuids;

    public const FILTER_SENT = 'sent';

    public const FILTER_RECEIVED = 'received';

    public const STATUS_SENT = 'read';

    public const STATUS_RECEIVED = 'received'; // @TODO figure out this one

    public const STATUS_READ = 'read';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'message',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'saved' => MessageSentEvent::class,
    ];

    /**
     * @return Builder<Message>
     */
    public static function getExchangedMessages(User $user, User $friend): Builder
    {
        return
            Message::whereIn('sender_user_id', [$user->id, $friend->id])
            ->whereIn('receiver_user_id', [$user->id, $friend->id]);
    }

    public static function getCountUnreadMessages(User $user, User $friend): int
    {
        return
            Message::where('sender_user_id', $friend->id)
            ->where('receiver_user_id', $user->id)
            ->whereNot('status', Message::STATUS_READ)
            ->count();
    }

    /**
     * @return BelongsTo<User,Message>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * @return BelongsTo<User,Message>
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }
}
