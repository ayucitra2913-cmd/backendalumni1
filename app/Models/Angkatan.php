<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    use HasFactory;

    protected $table = 'angkatan';

    protected $fillable = [
        'tahun_angkatan',
        'nama_angkatan',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'angkatan_id');
    }

    public function alumni()
    {
        return $this->hasMany(Alumni::class, 'angkatan_id');
    }
}
