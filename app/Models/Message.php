<?php

namespace App\Models;

use App\Events\MessageSentEvent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $id
 * @property string $status
 * @property string|null $message
 */
class Message extends Model
{
    use HasFactory, HasUuids;

    public const FILTER_SENT = 'sent';

    public const FILTER_RECEIVED = 'received';

    public const STATUS_SENT = 'sent';

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
     * @return BelongsTo<User,Message>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * @return BelongsTo<User,Message>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * @return HasOne<Zbra,Message>
     */
    public function zbra(): HasOne
    {
        return $this->hasOne(Zbra::class);
    }
}
