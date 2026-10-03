<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    protected $table = 'verifikasi';

    protected $fillable = [
        'user_id',
        'status',
        'role',
    ];

    /**
     * Relasi ke User yang mendaftar.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ---- Scope: status pending ---- */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /* ---- Scope: filter by role ---- */
    public function scopeForRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
