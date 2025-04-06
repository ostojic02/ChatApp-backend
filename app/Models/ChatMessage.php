<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table="chat_messages";
    protected $guarded=['id'];
    protected $touches=['chat'];

    public function user():BelongsTo{
        return $this->belongsTo(related:User::class, foreignKey:'user_id');
    }
    public function chat():BelongsTo{
        return $this->belongsTo(related:Chat::class, foreignKey:'chat_id');
    }
}
