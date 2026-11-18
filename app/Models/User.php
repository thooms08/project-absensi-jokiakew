<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'role',
        'image',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Relasi ke mapel yang diampu guru ini.
     */
    public function mapel()
    {
        return $this->belongsToMany(
            \App\Models\Mapel::class,
            'guru_mapel',
            'user_id',
            'mapel_id'
        );
    }

    /* ================================================================
       KONSTANTA ROLE
    ================================================================ */

    const ROLES_GURU  = ['guru_smk', 'guru_smp'];
    const ROLES_MURID = ['murid_smk', 'murid_smp'];

    /**
     * Apakah user ini termasuk kelompok guru?
     */
    public function isGuru(): bool
    {
        return in_array($this->role, self::ROLES_GURU, true);
    }

    /**
     * Apakah user ini termasuk kelompok murid?
     */
    public function isMurid(): bool
    {
        return in_array($this->role, self::ROLES_MURID, true);
    }

    /**
     * Jenis sekolah dari role: 'smk' | 'smp' | null
     */
    public function jenisSekolah(): ?string
    {
        if (str_ends_with($this->role, '_smk')) return 'smk';
        if (str_ends_with($this->role, '_smp')) return 'smp';
        return null;
    }

    /* ================================================================
       SCOPES
    ================================================================ */

    /**
     * Scope: hanya guru aktif (tidak di-softdelete) — semua jenis sekolah.
     */
    public function scopeGuruAktif($query)
    {
        return $query->whereIn('role', self::ROLES_GURU);
    }

    /**
     * Scope: guru yang sudah di-softdelete.
     */
    public function scopeGuruNonaktif($query)
    {
        return $query->onlyTrashed()->whereIn('role', self::ROLES_GURU);
    }

    /**
     * Scope: filter berdasarkan jenis sekolah ('smk' atau 'smp').
     */
    public function scopeJenisSekolah($query, string $jenis)
    {
        $jenis = strtolower($jenis);
        return $query->where(function ($q) use ($jenis) {
            $q->where('role', 'guru_' . $jenis)
              ->orWhere('role', 'murid_' . $jenis);
        });
    }
}
