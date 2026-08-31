<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function alumni()
    {
        return $this->hasOne(Alumni::class, 'user_id');
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'user_id');
    }

    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'user_id');
    }

    public function acara()
    {
        return $this->hasMany(Acara::class, 'user_id');
    }
}
