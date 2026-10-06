<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KodeAbsen extends Model
{
    protected $table    = 'kode_absen';
    protected $fillable = ['user_id', 'jadwal_mengajar_id', 'kode', 'expired_at', 'is_active'];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    /* Cek apakah kode masih valid */
    public function masihBerlaku(): bool
    {
        return $this->is_active && $this->expired_at && $this->expired_at->isFuture();
    }

    /* Relasi */
    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalMengajar::class, 'jadwal_mengajar_id');
    }
}
