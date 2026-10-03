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

    /**
     * Scope: hanya guru aktif (tidak di-softdelete).
     */
    public function scopeGuruAktif($query)
    {
        return $query->where('role', 'guru');
    }

    /**
     * Scope: guru yang sudah di-softdelete.
     */
    public function scopeGuruNonaktif($query)
    {
        return $query->onlyTrashed()->where('role', 'guru');
    }
}
