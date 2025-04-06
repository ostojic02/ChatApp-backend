<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

   
    protected $table='users';
    protected $guarded=['id'];
    protected $hidden=[ 'password' ];

    const USER_TOKEN="userToken";
    
    public function chats():HasMany{
        return $this->hasMany(related: Chat::class, foreignKey:'created_by');
    }
}
