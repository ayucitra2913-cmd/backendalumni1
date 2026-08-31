<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'angkatan_id',
        'kelas_id',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'telepon',
        'alamat',
        'pekerjaan_saat_ini',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pengurus()
    {
        return $this->hasMany(PengurusAlumni::class, 'alumni_id');
    }

    public function prestasi()
    {
        return $this->hasMany(PrestasiAlumni::class, 'alumni_id');
    }

    public function testimonies()
    {
        return $this->hasMany(Testimony::class, 'alumni_id');
    }
}
