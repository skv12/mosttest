<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Message extends Model
{
    use HasFactory;
    /**
     * Get the user that owns the Message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected $guarded = false;
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSeen(): HasMany
    {
        return $this->hasMany(MessageIsSeen::class);
    }
}
