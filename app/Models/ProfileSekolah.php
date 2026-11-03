<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSekolah extends Model
{
    protected $table    = 'profile_sekolah';
    protected $fillable = [
        'nama_sekolah',
        'logo',
        'deskripsi',
        'no_hp',
        'email',
        'alamat',
        'maps_link',
    ];

    /**
     * Ambil satu-satunya record profil sekolah (singleton).
     * Jika belum ada, buat otomatis dengan nilai default.
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate([], [
            'nama_sekolah' => null,
            'logo'         => null,
        ]);
    }
}
