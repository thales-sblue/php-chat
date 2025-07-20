<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory;

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

    public function conversationsAsRecipient(): HasMany
    {
        return $this->hasMany(Conversation::class, 'recipient_id');
    }
}
