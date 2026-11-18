<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table    = 'mapel';
    protected $fillable = ['nama_mapel', 'jenis_sekolah'];

    /* ---- Scope: filter mapel berdasarkan jenis sekolah ---- */
    public function scopeJenisSekolah($query, string $jenis)
    {
        return $query->where('jenis_sekolah', $jenis);
    }
}

