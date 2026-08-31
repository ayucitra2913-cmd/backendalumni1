<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiAlumni extends Model
{
    use HasFactory;

    protected $table = 'prestasi_alumni';

    protected $fillable = [
        'alumni_id',
        'nama_prestasi',
        'tingkat',
        'tahun_perolehan',
        'deskripsi',
        'sertifikat_url',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
