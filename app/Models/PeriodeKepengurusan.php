<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeKepengurusan extends Model
{
    protected $table =  'periode_kepengurusan';
    protected $fillable = ['nama_periode', 'tanggal_mulai', 'tanggal_selesai'];

        public function pengurus()
        {
            return $this->hasMany(PengurusAlumni::class, 'periode_id');
        }
}
