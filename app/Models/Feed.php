<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 */
class Feed extends Model
{
    use HasFactory, HasUuids;

    /**
     * @return BelongsTo<User,Feed>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<User,Feed>
     */
    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    /**
     * @return BelongsTo<Zbra,Feed>
     */
    public function zbra(): BelongsTo
    {
        return $this->belongsTo(Zbra::class, 'zbra_id');
    }

    public function countUnreadZbras(): int
    {
        /** @var User */
        $user = $this->user()->getResults();
        /** @var User */
        $friend = $this->friend()->getResults();

        return Zbra::getCountUnreadZbras($user, $friend);
    }
}
