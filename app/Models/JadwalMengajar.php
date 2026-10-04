<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JadwalMengajar extends Model
{
    protected $table    = 'jadwal_mengajar';
    protected $fillable = ['uuid', 'user_id', 'mapel_id', 'kelas_id', 'hari', 'waktu_mulai', 'waktu_berakhir'];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /* ---- Relasi ---- */
    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
