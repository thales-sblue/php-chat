<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'recipient_id',
        'content',
        'priority',
        'status',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'recipient_id');
    }
}
