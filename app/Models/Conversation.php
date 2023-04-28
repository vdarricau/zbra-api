<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * @TODO transform this into Conversation model
 *
 * @property string $id
 */
class Conversation extends Model
{
    use HasFactory, HasUuids;

    public static function findOneOnOne(User $user, User $friend): ?Conversation
    {
        if ($user->is($friend)) {
            throw new LogicException('Solo convo not supported yet.');
        }

        $conversationId = DB::table('conversations_users')
            ->where('user_id', $user->id)
            ->orWhere('user_id', $friend->id)
            ->groupBy('conversation_id')
            ->orderBy('created_at')
            ->limit(1)
            ->get()
            ->first()
            ?->conversation_id;

        return Conversation::find($conversationId);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversations_users', 'conversation_id', 'user_id');
    }

    /**
     * @return HasMany<Message>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
