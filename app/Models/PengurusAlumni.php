<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengurusAlumni extends Model
{
    use HasFactory;

    protected $table = 'pengurus_alumni';

    protected $fillable = [
        'alumni_id',
        'jabatan',
        'periode_id'
    ];

    public function periode()
    {
        return $this->belongsTo(PeriodeKepengurusan::class, 'periode_id');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
