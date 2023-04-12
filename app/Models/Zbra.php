<?php

namespace App\Models;

use App\Events\ZbraCreated;
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
        'saved' => ZbraCreated::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }

    public static function getExchangedZbras(User $user, User $friend): Builder
    {
        return 
            Zbra::whereIn('user_sender_id', [$user->id, $friend->id])
            ->whereIn('user_receiver_id', [$user->id, $friend->id])
        ;
    }
}
