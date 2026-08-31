<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'angkatan_id',
        'kelas',
    ];

    public function getNamaKelasAttribute()
    {
        return $this->attributes['kelas'] ?? null;
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function alumni()
    {
        return $this->hasMany(Alumni::class, 'kelas_id');
    }
}
