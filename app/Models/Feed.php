<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feed extends Model
{
    use HasFactory, HasUuids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function zbra(): BelongsTo
    {
        return $this->belongsTo(Zbra::class, 'zbra_id');
    }

    public function countUnreadZbras(): int
    {
        return Zbra::getCountUnreadZbras($this->user()->getResults(), $this->friend()->getResults());
    }
}
