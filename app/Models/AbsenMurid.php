<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AbsenMurid extends Model
{
    protected $table    = 'absen_murid';
    protected $fillable = [
        'uuid', 'user_id', 'jadwal_mengajar_id', 'tanggal_absen',
        'kode_absen', 'status', 'alasan',
        'foto_selfie', 'foto_izin', 'dikonfirmasi_at',
    ];

    protected $casts = [
        'tanggal_absen'   => 'date',
        'dikonfirmasi_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->uuid)) {
                $m->uuid = (string) Str::uuid();
            }
        });
    }

    public function murid()   { return $this->belongsTo(User::class, 'user_id'); }
    public function jadwal()  { return $this->belongsTo(JadwalMengajar::class, 'jadwal_mengajar_id'); }
}
