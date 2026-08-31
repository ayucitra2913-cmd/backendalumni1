<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albums';

    protected $fillable = [
        'user_id',
        'nama_album',
        'deskripsi',
        'sampul_gambar',
        'cover_image',
    ];

    public function getCoverImageAttribute()
    {
        return $this->attributes['sampul_gambar'] ?? ($this->attributes['cover_image'] ?? null);
    }

    public function setCoverImageAttribute($value)
    {
        $this->attributes['sampul_gambar'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'album_id');
    }
}
