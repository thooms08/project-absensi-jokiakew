<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbsenGuru extends Model
{
    protected $table    = 'absen_guru';
    protected $fillable = [
        'uuid',
        'user_id',
        'jadwal_mengajar_id',
        'tanggal_absen',
        'foto_bukti',
        'status',
        'alasan',
    ];

    protected $casts = [
        'tanggal_absen' => 'date',
    ];

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

    public function jadwal()
    {
        return $this->belongsTo(JadwalMengajar::class, 'jadwal_mengajar_id');
    }
}
