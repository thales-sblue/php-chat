<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'sender_id',
        'client_id',
        'last_message_content',
        'last_message_time',
        'unread_count',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sender_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
