<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function feedPosts() { // https://laravel.com/docs/10.x/eloquent-relationships#has-many-through-key-conventions
        return $this->hasManyThrough(Post::class, Follow::class, 'user_id', 'user_id', 'id', 'followeduser');

    }
    public function followers()
    {
        return $this->hasMany(Follow::class, 'followeduser'); // third argument is the local key or primary key on the current model
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id'); // third argument is the local key or primary key on the current model
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(get: function($value){
            return $value ? "/storage/avatars/".$value : '/fallback-avatar.jpg';
        });
    }
}
