<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $text
 * @property string $image_url
 * @property string $image_height
 * @property string $image_width
 */
class Zbra extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image_height',
        'image_width',
        'image_url',
        'text',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
