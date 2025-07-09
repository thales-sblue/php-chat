<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'cpf_cnpj',
        'phone',
        'balance',
        'limit',
        'status',
    ];

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function conversationsAsSender(): HasMany
    {
        return $this->hasMany(Conversation::class, 'sender_id');
    }

    public function conversationsAsReceiver(): HasMany
    {
        return $this->hasMany(Conversation::class, 'receiver_id');
    }
}
