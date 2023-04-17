<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Exceptions\ZbraCannotBeSentToNonFriendsException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LogicException;

/**
 * @property string $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return BelongsToMany<User>
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_connecting_id', 'friend_accepting_connection_id')->withTimestamps();
    }

    /**
     * @return HasMany<FriendRequest>
     */
    public function requestedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'sender_user_id');
    }

    /**
     * @return HasMany<FriendRequest>
     */
    public function friendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'receiver_user_id');
    }

    /**
     * @return HasMany<Feed>
     */
    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class, 'user_id');
    }

    public function addFriend(User $newFriend): void
    {
        if ($newFriend->is($this)) {
            throw new LogicException('Can\'t add yourself as your friend!');
        }

        $this->friends()->attach($newFriend);
        $newFriend->friends()->attach($this);
    }

    public function isFriend(User $friend): bool
    {
        return $this->friends()->where('friend_accepting_connection_id', $friend->id)->count() !== 0;
    }

    public function sendZbra(User $friend, string $message): Zbra
    {
        if (false === $this->isFriend($friend)) {
            throw new ZbraCannotBeSentToNonFriendsException;
        }

        $zbra = new Zbra();

        $zbra->message = $message;
        $zbra->receiver()->associate($friend);
        $zbra->sender()->associate($this);

        $zbra->saveOrFail();

        return $zbra;
    }
}
