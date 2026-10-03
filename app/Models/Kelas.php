<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kelas extends Model
{
    protected $table    = 'kelas';
    protected $fillable = ['uuid', 'nama_kelas'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($kelas) {
            if (empty($kelas->uuid)) {
                $kelas->uuid = (string) Str::uuid();
            }
        });
    }

    /* ---- Relasi ke peserta ---- */
    public function peserta()
    {
        return $this->hasMany(PesertaKelas::class, 'kelas_id');
    }

    public function waliKelas()
    {
        return $this->hasOne(PesertaKelas::class, 'kelas_id')
                    ->where('label', 'wali_kelas')
                    ->with('user');
    }

    public function guru()
    {
        return $this->hasMany(PesertaKelas::class, 'kelas_id')
                    ->whereIn('label', ['wali_kelas', 'guru_kelas'])
                    ->with('user');
    }

    public function murid()
    {
        return $this->hasMany(PesertaKelas::class, 'kelas_id')
                    ->where('label', 'murid')
                    ->with('user');
    }
}
