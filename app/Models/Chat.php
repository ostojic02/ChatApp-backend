<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $table="chats";
    protected $guarded=['id'];

    public function participants():HasMany{
        return $this->hasMany(related: ChatParticipant::class, foreignKey:'chat_id');
    }
    public function message():HasMany{
        return $this->hasMany(related: ChatMessage::class, foreignKey:'chat_id');
    }
    public function lastMessage(){
        return $this->hasOne(related: ChatMessage::class, foreignKey:'chat_id')->latest(column:'updated_at');
    }
    public function scopeHasParticipant($query, int $userId)
    {
    return $query->whereHas('participants', function($q) use ($userId) {
        $q->where('user_id', $userId);
    });
    }
}
