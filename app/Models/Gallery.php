<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'galleries';

    protected $fillable = [
        'album_id',
        'file_url',
        'keterangan',
        'caption',
    ];

    public function getCaptionAttribute()
    {
        return $this->attributes['keterangan'] ?? ($this->attributes['caption'] ?? null);
    }

    public function setCaptionAttribute($value)
    {
        $this->attributes['keterangan'] = $value;
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
