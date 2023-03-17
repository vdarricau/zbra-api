<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zbra extends Model
{
    use HasFactory, HasUuids;

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_receiver_id');
    }
}
