<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

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

    public function addFriend(User $newFriend): void
    {
        if ($newFriend->is($this)) {
            throw new \LogicException('Can\'t add yourself as your friend!');
        }

        $this->friends()->attach($newFriend);
        $newFriend->friends()->attach($this);
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_connecting_id', 'friend_accepting_connection_id')->withTimestamps();
    }

    public function isFriend(User $friend): bool
    {
        return $this->friends()->where('friend_accepting_connection_id', $friend->id)->count() !== 0;
    }

    public function requestedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'requester_id');
    }

    public function friendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'friend_id');
    }

    public function zbras(): HasMany
    {
        return $this->hasMany(Zbra::class, 'user_receiver_id');
    }

    public function sentZbras(): HasMany
    {
        return $this->hasMany(Zbra::class, 'user_sender_id');
    }

    /**
     * @return HasMany
     */
    public function feeds(): HasMany
    {
        return $this->hasMany(Feed::class, 'user_id');
    }
}
